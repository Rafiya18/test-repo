<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

// auth.php
session_start();
require_once "db.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

function sendAuthEmail($toEmail, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'shalinhannan7@gmail.com';
        $mail->Password   = 'jktt qfrz rxpp zxbg';   
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('shalinhannan7@gmail.com', 'Arovia');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        die("PHPMailer error encountered:.$mail->ErrorInfo");
    }
}

$action = $_POST['action'] ?? '';

// --- 1. SIGN UP (UPDATED FOR COLUMNS) ---
if ($action === 'signup') {
    $name = htmlspecialchars(strip_tags(trim($_POST['name'] ?? '')));
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (empty($name) || !$email || empty($password)) {
        die("Invalid details. All fields are required.");
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $otp = rand(100000, 999999);
    $otpExpiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

    try {
        
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, otp, otp_expires_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword, $otp, $otpExpiry]);

        $emailBody = "<h3>Welcome to Arovia, $name!</h3><p>Your OTP code is: <b>$otp</b></p>";
        sendAuthEmail($email, "Verify Your Account", $emailBody);

        $_SESSION['verify_email'] = $email;
        header("Location: index.html?view=verify&msg=otp_sent");
    } catch (PDOException $e) {
        header("Location: index.html?msg=email_exists");
    }
}

// --- 2. VERIFY OTP ---
if ($action === 'verify') {
    $email = $_SESSION['verify_email'] ?? '';
    $userOtp = $_POST['otp'] ?? '';

    if (empty($email) || empty($userOtp)) {
        die("Invalid request.");
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Uses 'otp_expires_at' to check time limits
    if ($user && $user['otp'] === $userOtp && strtotime($user['otp_expires_at']) > time()) {
        $stmt = $pdo->prepare("UPDATE users SET is_verified = 1, otp = NULL, otp_expires_at = NULL WHERE email = ?");
        $stmt->execute([$email]);
        unset($_SESSION['verify_email']);
        header("Location: index.html?view=login&msg=verified");
    } else {
        header("Location: index.html?view=verify&msg=invalid_otp");
    }
}

// --- 3. RESEND OTP ---
if ($action === 'resend_otp') {
    $email = $_SESSION['verify_email'] ?? '';
    if (empty($email)) {
        die("No session context.");
    }

    $otp = rand(100000, 999999);
    $otpExpiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

    // Uses 'otp_expires_at'
    $stmt = $pdo->prepare("UPDATE users SET otp = ?, otp_expires_at = ? WHERE email = ?");
    $stmt->execute([$otp, $otpExpiry, $email]);

    $emailBody = "<p>Your new OTP code is: <b>$otp</b></p>";
    sendAuthEmail($email, "Resend OTP", $emailBody);

    header("Location: index.html?view=verify&msg=otp_resent");
}

// --- 4. LOGIN ---
if ($action === 'login') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['is_verified'] == 0) {
            $_SESSION['verify_email'] = $email;
            header("Location: index.html?view=verify&msg=not_verified");
            exit;
        }
        $_SESSION['user'] = $user['email'];
        header("Location: dashboard.php");
        exit;
    }
}

// --- 5. FORGOT PASSWORD ---
if ($action === 'forgot_password') {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', strtotime('+30 minutes'));

    $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?");
    $stmt->execute([$token, $expiry, $email]);

    $resetLink = "http://localhost:8000/index.html?view=reset&token=" . $token;
    $emailBody = "<p>Click here to reset your password:</p><a href='$resetLink'>Reset Password</a>";
    sendAuthEmail($email, "Password Reset Request", $emailBody);

    header("Location: index.html?view=login&msg=reset_link_sent");
}

// --- 6. RESET PASSWORD ---
if ($action === 'reset_password') {
    $token = $_POST['token'] ?? '';
    $newPassword = $_POST['password'] ?? '';

    if (empty($token) || empty($newPassword)) {
        die("Invalid token context.");
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user && strtotime($user['reset_expiry']) > time()) {
        $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE id = ?");
        $stmt->execute([$newHash, $user['id']]);
        header("Location: index.html?view=login&msg=password_updated");
    } else {
        header("Location: index.html?view=login&msg=token_expired");
    }
}