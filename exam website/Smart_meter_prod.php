<?php
session_start();

// User Authentication Check
if (!isset($_SESSION['username'])) {
    header("Location: form_login.php");
    exit();
}

// Include the database connection file
include 'database_con.php';

// Get a database connection
$conn = get_database_connection();

// Check for an active connection, else exit:
if (!$conn) {
    die("Database connection failed.");
}

$username = $_SESSION['username'];

// This will need a Prepare statement in the future!
$sql = "SELECT * FROM user_details WHERE username = '$username'";  // Adjust table name if needed
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $email = $user['email']; // Assuming you have an email field
    $user_id = $user['User_Id'];
    $_SESSION['user_id'] = $user_id; // Store user ID in session
} else {
    // Handle the case where the user is not found in the database.
    $email = "User data not found";  // Or redirect, or display an error
    $user_id = null;
}

// If you wish to keep the code like that.
// With Injection Code:
$username = $_SESSION['username']; // Assuming username is the unique identifier

// Fetch user data from the database
$sql = "SELECT * FROM user_details WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $email = $user['email']; // Assuming you have an email field
    $user_id = $user['User_Id'];
    $_SESSION['user_id'] = $user_id; // Store user ID in session
} else {
    $email = "User data not found";
    $user_id = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fast Charging EV Charger | RolsaTech</title>
    <link rel="stylesheet" href="../CSS/products.css">
</head>
<body>
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
</ul>
        <!--// end of navbar // -->

        <!--// hamburger menu to allow users to navigate website on smaller devices which cant fit the navbar so iyt combines into this menu // -->
        <div class="hamburger-menu" onclick="toggleMenu()">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </nav>
    <div class="product-container">
        <div class="product-image">
            <img src="../images/Smart_meter.jpg" alt="Smart Energy Monitor">
        </div>
        <div class="product-details">
            <h1 class="product-title">Smart Energy Monitor</h1>
            <p class="product-price">Price: Request a Quote</p>

            <div class="product-description">
                <h3>Product Overview</h3>
                <p>Take control of your energy consumption with our intuitive Smart Energy Monitor.  Designed to provide real-time insights into your electricity usage, this device empowers you to make informed decisions, reduce waste, and save money on your energy bills.</p>
            </div>

            <div class="product-specs">
                <h3>Technical Specifications</h3>
                <table class="specs-table">
                    <tr>
                        <td class="spec-label">Metering Accuracy</td>
                        <td>Class 1 (IEC 62053-21)</td>
                    </tr>
                    <tr>
                        <td class="spec-label">Voltage Range</td>
                        <td>100-240V AC</td>
                    </tr>
                    <tr>
                        <td class="spec-label">Current Range</td>
                        <td>0-100A</td>
                    </tr>
                    <tr>
                        <td class="spec-label">Frequency</td>
                        <td>50/60 Hz</td>
                    </tr>
                    <tr>
                        <td class="spec-label">Connectivity</td>
                        <td>Wi-Fi (2.4 GHz), Optional: Ethernet</td>
                    </tr>
                    <tr>
                        <td class="spec-label">Data Logging</td>
                        <td>Internal Memory, Cloud Storage</td>
                    </tr>
                    <tr>
                        <td class="spec-label">Display</td>
                        <td>LCD Display with Backlight</td>
                    </tr>
                    <tr>
                        <td class="spec-label">Mobile App Compatibility</td>
                        <td>iOS, Android</td>
                    </tr>
                    <tr>
                        <td class="spec-label">Warranty</td>
                        <td>1 Year Limited Warranty</td>
                    </tr>
                </table>
            </div>

            <div class="product-benefits">
                <h3>Key Benefits</h3>
                <ul>
                    <li>Real-time energy consumption monitoring</li>
                    <li>Detailed insights into usage patterns</li>
                    <li>Identify energy-wasting appliances</li>
                    <li>Track energy savings over time</li>
                    <li>Remote access via mobile app</li>
                </ul>
            </div>

            <a href="book_form.php" class="product-cta">Request a Quote</a>
        </div>
    </div>
    <script src="javascript/navigation.js" defer></script>
</body>
</html>