<?php
session_start();

// Function to check if the user is logged in
function isLoggedIn()
{
    return isset($_SESSION['username']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../CSS/index.css">

    <script src="../javascript/accessibility.js" defer></script> <!-- bringing in code for the accessibility functionailty of the website // -->
    <script src="../javascript/navigation.js" defer></script> <!-- // brings in Js code for the hamburger menu functionality // -->
    <script src="../javascript/as.js" defer></script> 
    <script src="../javascript/asopenclose.js" defer></script>
    <script src="../javascript/initialisation.js" defer></script>

</head>
<body>

    <!--//Cookie consenty functionality to ensure the user allows us to use cookies//-->
    <div id="cookie-overlay">
        <h1>We use cookies on this website.</h1>
        <p>This is so that we can store information about you on this website. These cookies will only be used on this website.</p>
        <br><br>
        <button id="cookie-accept" onclick="closeCookieConsent()">OK</button>
    </div>
    <!--//end of cookie consent code //-->


    <!--// navbar functionality allows the user to navigate the website from anypoint easily--> 
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
                <li class="hamburger-login"><a href="form_login.php">Login</a></li>
                <li class="hamburger-register"><a href="form_register.php">Register</a></li>
            </ul>
            <div class="nav-right">
                <ul class="navbar">
                    <li><a href="form_login.php">Login</a></li>
                    <li><a href="form_reg.php">Register</a></li>
                </ul>
            </div>
            <?php } ?>

        <!--// hamburger menu to allow users to navigate website on smaller devices which cant fit the navbar so iyt combines into this menu // -->
        <div class="hamburger-menu" onclick="toggleMenu()">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </nav>
    <!-- // end of hambuger menu // -->


    <!-- // Hero section first thing the user will see // -->
    <div class="hero">
        <h1>Welcome to Rolsa Technologies</h1>
        <p><a href="Shop.html">Shop our products here</a></p>
    </div>


    <!-- // second hero section used to give info// -->
    <div class="hero2">
        <div class="hero-text">
            <h1>Powering Change Together</h1>
            <p>
                Become part of a forward-thinking community,<br>
                committed to making a positive impact on the planet<br>
                through sustainable technologies<br>
                and conscious living.
            </p>
            
            <p>
                <strong>Rolsa Technologies:</strong><br>
                Powering a Sustainable Future from Little Neston, England.
            </p>
            
            <p>
                We've helped over 30,000 businesses worldwide achieve their sustainability goals,<br>
                and contributed to saving thousands of metric tons of energy globally.
            </p>
            
            <p>
                Join us in building a more sustainable future, right here in Little Neston and beyond.<br>
                We believe that by combining innovative technology with conscious living,<br>
                we can create a brighter tomorrow for our planet and our community.
            </p>
        </div>
        <div class="hero-image">
            <img src="../images/ev_hero.jpg" alt="Zoo Adventure"> <!--// image to go to the right of the text // -->
        </div>
    </div>
    <!-- // end of second hero // -->


    <!--//navigation boxes for navigating website easier and accessibility //-->
    <div class="container-row">
        <a href="Shop.html" class="box">
            <img src="../images/ev_shop.jpg" alt="ROlSA Technologies EV shop"> <!--// images to show wha the link takes you to incase user cant read // -->
            <p>EV Shop</p>
        </a>
        <a href="About.html" class="box">
            <img src="../images/about_rolsa.jpg" alt="About ROLSA Technologies">
            <p>About Us</p>
        </a>
        <a href="Green_insights.html" class="box">
            <img src="../images/green_insights1.jpg" alt="Green energy insights">
            <p>Green Insights</p>
        </a>
    </div>

    <!--// end of navigation boxes //-->


    <!--//navigation functionality for website //-->
    <div class="accessibility-tab" onclick="toggleAccessibility()">♿</div> <!--// this defines the accessibility tab on the website and refers the functions from the JS code//-->
    <div class="accessibility-panel" id="accessibilityPanel">
        <h3>Accessibility Options</h3>
        <button onclick="increaseTextSize()">Increase Text Size</button>
        <button onclick="decreaseTextSize()">Decrease Text Size</button>
        <button onclick="toggleContrast()">Toggle High Contrast</button>
    </div>
    <!--// end of navigation functionality //-->

</body>
<footer>
    <p>&copy; 2025 All Rights Reserved. Designed by Ewan Evans.</p>
</footer>
</html>