<?php 
session_start();

// Check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['username']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="stylesheet" href="../CSS/about.css">

    <script src="../javascript/accessibility.js" defer></script>
    <script src="../javascript/navigation.js" defer></script>
    <script src="../javascript/as.js" defer></script>
    <script src="../javascript/asopenclose.js" defer></script>
    <script src="../javascript/initialisation.js" defer></script>

</head>
<body>
    
    <nav>
        <div class="nav-left">
            <img src="../images/logo2.png" alt="Rolsa Technologies Logo" class="logo">
        </div>

        <!-- Centered navigation links -->
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
                    <li><a href="PHP/form_login.php">Login</a></li>
                    <li><a href="PHP/form_reg.php">Register</a></li>
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

    <div class="hero2"> <!-- Re-using hero2 for consistent styling -->
        <div class="hero-text">
            <h1>About Rolsa Technologies</h1>
            <p>Powering a Sustainable Future</p>
            <b>Our Mission:</b><br>
            <p>At Rolsa Technologies, we are dedicated to driving the transition to a cleaner, more sustainable future by providing innovative ecotech solutions.</p>
            <b>What We Offer:</b><br>
            <ul>
                <li><b>Solar Panels:</b> High-efficiency solar panels for residential, commercial, and industrial applications. Harness the power of the sun and reduce your carbon footprint.</li>
                <li><b>EV Chargers:</b> Fast and reliable electric vehicle chargers for homes, businesses, and public spaces. Power your journey with clean energy.</li>
                <li><b>Eco Tools:</b> A range of sustainable tools and gadgets to help you live a greener lifestyle. From energy monitors to water-saving devices, we have everything you need to make a difference.</li>
            </ul>
            <p>We are committed to providing our customers with high-quality products, expert advice, and exceptional service. Join us in building a brighter, more sustainable tomorrow.</p>
        </div>
    </div>

    <div class="hero-image">
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d873580.2374544299!2d-3.291320757771176!3d52.78725833219547!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x487af025d8870429%3A0x23c56cc1576d4d71!2sPortal%20Business%20Park!5e0!3m2!1sen!2suk!4v1742747790620!5m2!1sen!2suk"
                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade" title="Riget Zoo Adventures Location on Google Maps"></iframe>
        </div>
    </div>

    <!-- Top 4 Smaller Boxes -->
    <div class="container-row">
        <a href="Index.html" class="box">
            <img src="../images/about_rolsa.jpg" alt="Home Page">
            <p>Home</p>
        </a>
        <a href="Shop.html" class="box">
            <img src="../images/ev_shop.jpg" alt="ROlSA Technologies EV shop">
            <p>EV Shop</p>
        </a>
        <a href="Green_insights.html" class="box">
            <img src="../images/green_insights.jpg" alt="Green energy insights">
            <p>Green Insights</p>
        </a>
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
    <p>&copy; 2025 All Rights Reserved. Designed by Ewan Evans.</p>
</footer>  

</html>
