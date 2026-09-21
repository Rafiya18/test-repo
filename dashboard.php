<?php
// Start the session to check if the user is logged in
session_start();

// Determine login status (assumes your auth system sets $_SESSION['user_id'] or similar)
$isLoggedIn = isset($_SESSION['user_id']) || isset($_SESSION['logged_in']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AROVIA</title>
    <style>
        body {
            background-color: #FFE5C8;
            color: rgb(96, 39, 20);
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-style: italic;
            font-weight: 100;
            font-size: medium;
            margin: 0;
            padding: 0;
        }

        /* Navbar Layout styling */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 15px;
            background-color: #FFE5C8;
            padding: 14px 25px;
            margin-top: 40px;
        }

        .navbar a {
            text-decoration: none;
            color: #555;
            font-size: 18px;
            font-weight: 500;
            padding: 12px 22px;
            border-radius: 30px;
            transition: 0.3s ease;
        }

        .navbar a:hover {
            background-color: #f3e1d3;
            color: #341503;
        }

        .navbar a.active {
            background-color: #723105;
            color: white;
            font-weight: 600;
        }

        /* Side Navigation Panel Toggle Menu Styling */
        .menu-toggle {
            display: none;
        }

        .hamburger-icon span {
            display: block;
            height: 3px;
            width: 20px;
            background: rgb(96, 39, 20);
            margin: 5px 0;
        }

        .menu-panel {
            position: fixed;
            height: 70%;
            width: 250px; /* Fixed standard width so links are visible */
            background: #fdf1e4;
            padding-top: 10px;
            display: flex;
            flex-direction: column;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 1000;
        }

        .menu-panel a {
            padding: 10px;
            color: rgb(96, 39, 20);
            text-decoration: none;
            border-bottom: 1px solid #d2b48c;
        }

        .menu-panel a:hover {
            background-color: #f3e1d3;
        }

        .menu-toggle:checked ~ .menu-panel {
            transform: translateX(0);
        }

        /* General Typography & Elements styling */
        h1 span {
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', sans-serif;
        }

        input[type="text"] {
            border-radius: 25px;
            border: 1px solid #d2b48c;
            padding: 10px 18px;
        }

        input[type="button"] {
            background-color: rgb(96, 39, 20);
            color: #fff;
            border: 1px solid #d2b48c;
            padding: 5px 15px;
            border-radius: 12px;
            cursor: pointer;
        }

        img:hover {
            transform: scale(1.03);
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body>

    <header>
        <!-- Logo Header table -->
        <table width="100%">
            <tr>
                <td width="50%">
                    <div align="top">
                        <!-- logo of arovia -->
                        <img src="AROVIA.png" alt="Company Logo" align="left" height="100">
                    </div>
                </td>
                <td>
                    <!-- Right Navbar Links -->
                    <nav align="right" valign="top" class="navbar">
                        <a href="home.php" class="active">Home</a>
                        <a href="recipe.html">Recipe</a>
                        <a href="about.html">About</a>
                        <a href="contact.html">Contact</a>
                        
                        <?php if ($isLoggedIn): ?>
                            <!-- Show Dashboard and Logout buttons if user is authenticated -->
                            <a href="dashboard.php">Dashboard</a>
                            <a href="logout.php" style="background-color: #dc3545; color: white;">Logout</a>
                        <?php else: ?>
                            <!-- Show Signup and Login buttons if user is an unauthenticated guest -->
                            <a href="auth.php?action=signup">Signup</a>
                            <a href="auth.php?action=login">Login</a>
                        <?php endif; ?>
                    </nav>
                </td>
            </tr>
        </table>
    </header>

    <!-- Side Menu Toggle Switch -->
    <input type="checkbox" id="menuToggle" class="menu-toggle">
    <label for="menuToggle" class="hamburger-icon">
        <span></span>
        <span></span>
        <span></span>
    </label>

    <!-- Sidebar regional navigation list -->
    <nav class="menu-panel">
        <a href="indian.html">India</a>
        <a href="chinese.html">China</a>
        <a href="japanese.html">Japan</a>
        <a href="korean.html">South Korea</a>
        <a href="thai.html">Thailand</a>
        <a href="vietnamese.html">Vietnam</a>
        <a href="filipino.html">Philippines</a>
        <a href="indonesian.html">Indonesia</a>
        <a href="malaysian.html">Malaysia</a>
    </nav>

    <!-- Main Content Section -->
    <section>
        <br><br>
        <div align="center">
            <h1><i>WELCOME TO AROVIA</i></h1>
            <p>
                Dear viewer 👋, we wholeheartedly welcome you to our website.<br>
                Here, you can find recipes of your favourite cuisine in just few seconds.<br>
                Our website helps you find delicious meals with whatever ingredients you have.<br>
                So, you don't have to worry about it 🥘.<br>
                Hope you enjoy it.
            </p>

            <!-- Recipe Search bar container -->
            <form>
                <input type="text" placeholder="Search recipes, ingredients..." size="40">
                <input type="button" value="Search">
            </form>
            
            <br><br><br>

            <!-- Recipe Image Showcase Grids -->
            <table border="0" align="center" cellspacing="5">
                <tr>
                    <td><img src="https://pinimg.com" alt="Recipe 1" width="300"></td>
                    <td><img src="https://pinimg.com" alt="Recipe 2" width="300"></td>
                    <td><img src="https://pinimg.com" alt="Recipe 3" width="300"></td>
                </tr>
                <tr>
                    <td><img src="https://pinimg.com" alt="Recipe 4" width="300"></td>
                    <td><img src="https://pinimg.com" alt="Recipe 5" width="300"></td>
                    <td><img src="https://pinimg.com" alt="Recipe 6" width="300"></td>
                </tr>
                <tr>
                    <td><img src="https://pinimg.com" alt="Recipe 7" width="300"></td>
                    <td><img src="https://pinimg.com" alt="Recipe 8" width="300"></td>
                    <td><img src="https://pinimg.com" alt="Recipe 9" width="300"></td>
                </tr>
            </table>
        </div>
    </section>

    <!-- Footer Copyright area -->
    <footer>
        <hr>
        <p align="center">&copy; 2026 AROVIA - A journey through aromas and flavours.</p>
    </footer>

</body>
</html>