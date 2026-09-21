<?php
$host = "127.0.0.1";
$dbname = "auth_op";
$username = "root";
$password = "ElenaDOM@2026";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",$username,$password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    
    die("Database connection failed:" . $e->getMessage());
}