<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
        <link rel="stylesheet" href="../CSS/shop.css">
    
        <script src="../javascript/accessibility.js" defer></script>
        <script src="../javascript/navigation.js" defer></script>
        <script src="../javascript/as.js" defer></script>
        <script src="../javascript/asopenclose.js" defer></script>
        <script src="../javascript/initialisation.js" defer></script>
    
    </head>
<body>
<?php
    session_start();

    // User Authentication Check
    if (!isset($_SESSION['username'])) {
        // If no username is set in the session, redirect to login page
        header("Location: form_login.php");
        exit(); // Stop further script execution
    }
    ?>

<nav>
        <div class="nav-left">
            <img src="../images/logo2.png" alt="Rolsa Technologies Logo" class="logo">
        </div>

        <ul class="navbar">
        <?php if (isLoggedIn()) { ?>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="index.php">Home</a></li>
                <li><a href="Shop.php">Shop</a></li>
                <li><a href="About.php">About Us</a></li>
                <li><a href="Green_insights.php">Green Insights</a></li>
                <li class="hamburger-login"><a href="logout.php">Logout</a></li>
            </ul>
            <div class="nav-right">
                <ul>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
            <?php } else { ?>
                <li><a href="index.php">Home</a></li>
                <li><a href="Shop.php">Shop</a></li>
                <li><a href="About.php">About Us</a></li>
                <li><a href="Green_insights.php">Green Insights</a></li>
                <li class="hamburger-login"><a href="form_login">Login</a></li>
                <li class="hamburger-register"><a href="form_register">Register</a></li>
            </ul>
            <div class="nav-right">
                <ul class="navbar">
                    <li><a href="form_login.php">Login</a></li>
                    <li><a href="form_reg.php">Register</a></li>
                </ul>
            </div>
            <?php } ?>

        <!-- Hamburger Icon for small screens -->
        <div class="hamburger-menu" onclick="toggleMenu()">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </nav>

    <!-- First Hero Section (with Shop message) -->
    <div class="book_hero">
        <h1>Explore Sustainable Solutions at Rolsa Technologies</h1>
        <p>Browse our selection of solar panels, EV chargers, and eco-friendly tools.</p>
    </div>

    <!-- Product Display -->
    <div class="container-row">
         <a href="Solar_Products.php" class="product-box">
            <img src="../images/solar_img.jpg" alt="Solar Panel">
            <h3>High-Efficiency Solar Panel</h3>
            <p>Harness the power of the sun. </p>
            <p class = "price"> Price on request</p>
        </a>

        <a href="Ev_Products.php" class="product-box">
             <img src="../images/ev_hero.jpg" alt="EV Charger">
            <h3>Fast Charging EV Charger</h3>
            <p>Charge your electric vehicle quickly and efficiently.</p>
              <p class = "price"> Price on request</p>
        </a>

        <a href="Smart_meter_prod.php" class="product-box">
            <img src="../images/Smart_meter.jpg" alt="Eco Tool">
            <h3>Smart Energy Monitor</h3>
            <p>Track your energy consumption and save money. </p>
              <p class = "price"> Price on request</p>
        </a>
    </div>

    <!-- Login/Register Call to Action -->
    <div class="container-row">
        <a href="book_form.php" class="login-box">Book or Enquire about installation</a>
    </div>
   <!-- Accessibility Tab -->
<div class="accessibility-tab" onclick="toggleAccessibility()">♿</div>
<div class="accessibility-panel" id="accessibilityPanel">
    <h3>Accessibility Options</h3>
    <button onclick="increaseTextSize()">Increase Text Size</button>
    <button onclick="decreaseTextSize()">Decrease Text Size</button>
    <button onclick="toggleContrast()">Toggle High Contrast</button>
</div>
</body>
<footer>
    <p>© 2025 All Rights Reserved. Designed by Ewan Evans.</p>
  </footer>
</html>