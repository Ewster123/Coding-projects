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
try {
    $conn = get_database_connection();
    if (!$conn) {
        throw new Exception("Database connection failed.");
    }
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("A critical error occurred. Please try again later.");
}

$username = $_SESSION['username'];

// Fetch user details (Prepared Statement!)
$sql = "SELECT * FROM user_details WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $email = $user['email'];
    $user_id = $user['User_Id'];
    $_SESSION['user_id'] = $user_id; // Store user ID in session
} else {
    // Handle the case where the user is not found in the database.
    $email = "User data not found";
    $user_id = null;
}
$stmt->close();

// Display error message if it exists
if (isset($_SESSION['booking_error_message'])) {
    echo '<div class="error-message" style="color: red; padding: 10px; margin-bottom: 15px; background-color: #ffe6e6; border: 1px solid red; border-radius: 5px;">';
    echo htmlspecialchars($_SESSION['booking_error_message']); //Use htmlspecialchars
    echo '</div>';
    // Clear the message after displaying it
    unset($_SESSION['booking_error_message']);
}

// Display success message if it exists (optional)
if (isset($_SESSION['booking_success_message'])) {
    echo '<div class="success-message" style="color: green; padding: 10px; margin-bottom: 15px; background-color: #e6ffe6; border: 1px solid green; border-radius: 5px;">';
    echo htmlspecialchars($_SESSION['booking_success_message']); // Use htmlspecialchars
    echo '</div>';
    // Clear the message after displaying it
    unset($_SESSION['booking_success_message']);
}

// Get all the data for select lists.
// Get Engineers
$engineers = "SELECT engineer_id, name FROM engineers";
$engineers_result = $conn->query($engineers);

// Service
$service_types = "SELECT type_id, typename FROM service_type";
$service_result = $conn->query($service_types);

// Days
$days = "SELECT day_id, days FROM days";
$days_result = $conn->query($days);

$conn->close();

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="/CSS/account.css">
     <style>
        :root {
            --text-scale: 1;
        }
    </style>
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

    <div class="hamburger-menu" onclick="toggleMenu()">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </div>
</nav>

<div class="booking_hero">
    <h1>Book your appointment now!</h1>
    <p>Get started by selecting your booking type, date, and time.</p>
</div>

<div class="form_container_row">
    <div class="form-box">
        <form action="/PHP/submit_booking.php" method="post">
            <!-- Include the user's ID as a hidden field -->
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">

            <label for="type_id">Select Service Type:</label>
            <select id="type_id" name="type_id" required>
                <?php
                if ($service_result->num_rows > 0) {
                     while ($row = $service_result->fetch_assoc()) {
                          echo "<option value='" . htmlspecialchars($row["type_id"]) . "'>" . htmlspecialchars($row["typename"]) . "</option>";
                     }
                } else {
                     echo "<option value=''>No service types found</option>";
                }
                ?>
            </select>

            <label for="engineer_id">Select Engineer:</label>
            <select id="engineer_id" name="engineer_id" required>
                 <?php

                if ($engineers_result->num_rows > 0) {
                     while ($row = $engineers_result->fetch_assoc()) {
                          echo "<option value='" . htmlspecialchars($row["engineer_id"]) . "'>" . htmlspecialchars($row["name"]) . "</option>";
                     }
                } else {
                     echo "<option value=''>No engineers found</option>";
                }
                 ?>
            </select>

            <label for="day_id">Select Day:</label>
            <select id="day_id" name="day_id" required>
                <?php
                if ($days_result->num_rows > 0) {
                     while ($row = $days_result->fetch_assoc()) {
                          echo "<option value='" . htmlspecialchars($row["day_id"]) . "'>" . htmlspecialchars($row["days"]) . "</option>";
                     }
                } else {
                     echo "<option value=''>No Days found</option>";
                }
                 ?>
            </select>

            <label for="time">Select Time (AM/PM):</label>
            <select id="time" name="time" required>
                <option value="AM">AM</option>
                <option value="PM">PM</option>
            </select>
            <button type="submit">Book Appointment</button>

        </form>
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