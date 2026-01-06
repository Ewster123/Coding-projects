<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: form_login.php");
    exit();
}

include 'db_connection.php';

$conn = get_database_connection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $service_type = $_POST['service_type'];
    $product = $_POST['product'];
    $service_date = $_POST['service_date'];

    $stmt = $conn->prepare("INSERT INTO services (user_id, service_type, product, service_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $service_type, $product, $service_date);

    if ($stmt->execute()) {
        $_SESSION['booking_success_message'] = "Your service has been successfully booked!";
    } else {
        $_SESSION['booking_error_message'] = "Error booking service. Please try again.";
    }

    $stmt->close();
    $conn->close();
    header("Location: book_service.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Service | Rolsa Technologies</title>
    <link rel="stylesheet" href="style_booking.css">
</head>
<body>
    <nav>
        <div class="nav-left">
            <span class="company-name">Rolsa Technologies</span>
        </div>
        <ul class="navbar">
            <li><a href="../index.html">Home</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
        <div class="nav-right">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="booking_hero">
        <h1>Book a Service</h1>
        <p>Select the type of service you need and choose a date</p>
    </div>

    <div class="booking_container">
        <?php
        if (!empty($_SESSION['booking_error_message'])): ?>
            <p class="error-message"><?php echo $_SESSION['booking_error_message']; ?></p>
            <?php unset($_SESSION['booking_error_message']);
        endif;

        if (!empty($_SESSION['booking_success_message'])): ?>
            <p class="success-message"><?php echo $_SESSION['booking_success_message']; ?></p>
            <?php unset($_SESSION['booking_success_message']);
        endif;
        ?>

        <form id="bookingForm" action="book_service.php" method="POST">

            <div class="form-group">
                <label for="service_type">Select Service Type:</label>
                <select id="service_type" name="service_type" required>
                    <option value="installation">Installation</option>
                    <option value="consultation">Consultation</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>

            <div class="form-group">
                <label for="product">Select Product:</label>
                <select id="product" name="product" required>
                    <option value="solar_panels">Solar Panels</option>
                    <option value="ev_chargers">EV Chargers</option>
                    <option value="smart_home">Smart Home</option>
                </select>
            </div>

            <div class="form-group">
                <label for="service_date">Select Date:</label>
                <input type="date" id="service_date" name="service_date" required>
            </div>

            <button type="submit">Book Service</button>
        </form>
    </div>
</body>
</html>
