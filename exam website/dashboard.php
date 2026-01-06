<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rolsa Technologies Dashboard</title>
    <link rel="stylesheet" href="../CSS/dashboard.css">
    <script src="../javascript/navigation.js" defer></script> <!-- // brings in Js code for the hamburger menu functionality // -->
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
        <li><a href="dashboard.php">dashboard</a></li>
        <li><a href="index.php">Home page</a></li>
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

        
    <!--// hamburger menu to allow users to navigate website on smaller devices which cant fit the navbar so iyt combines into this menu // -->
    <div class="hamburger-menu" onclick="toggleMenu()">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </nav>
    <!-- // end of hambuger menu // -->

    <div class="dashboard">
        <h1>Welcome, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>!</h1>
        <p>Bring Your Vision to Life</p>
        <div class="dashboard-widgets">

        <div class="widget">
            <div class="widget-content">
                <h2>Shop products</h2>
                <p>Shop our range of products</p>
            </div>
            <div class="widget-button-container">
                <a href="Shop.php" class="btn">Shop Now</a>
            </div>
        </div>

        <div class="widget">
            <div class="widget-content">
                <h2>Your Bookings</h2>
                <p>Check out your booking schedule.</p>
            </div>
            <div class="widget-button-container">
                <a href="view_bookings.php" class="btn">View Bookings</a>
            </div>
        </div>

        <div class="widget">
            <div class="widget-content">
                <h2>Make a Booking</h2>
                <p>Book a installation or an enquiry call about our products.</p>
            </div>
            <div class="widget-button-container">
                <a href="book_form.php" class="btn">Book Now</a>
            </div>
        </div>

        </div>
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