<?php
session_start();

// User Authentication Check
if (!isset($_SESSION['username'])) {
    header("Location: form_login.php"); // Or wherever your login form is
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - Rolsa Technologies</title>
    <link rel="stylesheet" href="../CSS/confirmation.css">
</head>
<body>
    <nav>
        <div class="nav-left">
            <img src="/images/logo2.png" alt="Rolsa Technologies Logo" class="logo">
        </div>
        <ul class="navbar">
        <li><a href="dashboard.php">dashboard</a></li>
        <li><a href="Shop.php">Shop Products</a></li>
        <li><a href="View_bookings.php">Your Bookings</a></li>
        <li><a href="book_form.php">Make a Booking</a></li>
        <li><a href="settings.php">Settings</a></li>
    </ul>
        <div class="nav-right">
            <ul>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav> 
    
    <div class="confirmation-container">
        <h1>Booking Confirmed!</h1>
        <p class="confirmation-message">Thank you for your booking with Rolsa Technologies. We appreciate your business.</p>
        <a href="View_bookings.php" class="view-bookings-link">View and Manage Your Bookings</a>
    </div>

    <div class="accessibility-tab" onclick="toggleAccessibility()">♿</div>
    <div class="accessibility-panel" id="accessibilityPanel">
        <h3>Accessibility Options</h3>
        <button onclick="increaseTextSize()">Increase Text Size</button>
        <button onclick="decreaseTextSize()">Decrease Text Size</button>
        <button onclick="toggleContrast()">Toggle High Contrast</button>
    </div>

    <script>
        function toggleAccessibility() {
            document.getElementById('accessibilityPanel').classList.toggle('active');
        }

        let textScale = parseFloat(localStorage.getItem("textScale")) || 1;
        function increaseTextSize() {
            textScale += 0.2;
            document.documentElement.style.setProperty("--text-scale", textScale);
            localStorage.setItem("textScale", textScale);
        }
        function decreaseTextSize() {
            textScale = Math.max(0.6, textScale - 0.2);
            document.documentElement.style.setProperty("--text-scale", textScale);
            localStorage.setItem("textScale", textScale);
        }
        function toggleContrast() {
            document.body.classList.toggle("high-contrast");
            localStorage.setItem("highContrast", document.body.classList.contains("high-contrast") ? "enabled" : "disabled");
        }
    </script>
</body>
</html>