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

// Use a prepared statement to prevent SQL Injection
$sql = "SELECT * FROM user_details WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $email = $user['email']; // Assuming you have an email field
    $user_id = $user['User_Id'];
    $_SESSION['user_id'] = $user_id; // Store user ID in session
} else {
    // Handle the case where the user is not found in the database.
    $email = "User data not found";
    $user_id = null;
}

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

        <!-- Hamburger menu for small screens -->
        <div class="hamburger-menu" onclick="toggleMenu()">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </nav>

    <div class="product-container">
        <div class="product-image">
            <img src="../images/ev_hero.jpg" alt="Fast Charging EV Charger">
        </div>
        <div class="product-details">
            <h1 class="product-title">Fast Charging EV Charger</h1>
            <p class="product-price">Price: Request a Quote</p>

            <div class="product-description">
                <h3>Product Overview</h3>
                <p>Revolutionize your electric vehicle charging experience with our state-of-the-art Fast Charging EV Charger.  Engineered for rapid charging and user-friendly operation, this charger is the perfect addition to homes, businesses, and public charging stations.</p>
            </div>

            <div class="product-specs">
                <h3>Technical Specifications</h3>
                <table class="specs-table">
                    <tr><td class="spec-label">Charging Standard</td><td>CCS/SAE J1772 or CHAdeMO (Specify)</td></tr>
                    <tr><td class="spec-label">Charging Power</td><td>50kW - 350kW (Specify)</td></tr>
                    <tr><td class="spec-label">Input Voltage</td><td>400V AC or 480V AC (Specify)</td></tr>
                    <tr><td class="spec-label">Output Voltage</td><td>150-1000V DC</td></tr>
                    <tr><td class="spec-label">Connectivity</td><td>Ethernet, Wi-Fi, Optional Cellular</td></tr>
                    <tr><td class="spec-label">Safety Standards</td><td>IEC 61851, UL 2202</td></tr>
                    <tr><td class="spec-label">Warranty</td><td>2 Years Limited Warranty</td></tr>
                </table>
            </div>

            <div class="product-benefits">
                <h3>Key Benefits</h3>
                <ul>
                    <li>Ultra-fast charging capabilities</li>
                    <li>User-friendly interface and operation</li>
                    <li>Remote monitoring and management</li>
                    <li>Compliance with industry safety standards</li>
                    <li>Suitable for various electric vehicle models</li>
                </ul>
            </div>

            <a href="book_form.php" class="product-cta">Request a Quote</a>
        </div>
    </div>

    <script src="../javascript/navigation.js" defer></script>
</body>
</html>
