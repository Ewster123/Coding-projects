<?php
session_start();

// User Authentication Check
if (!isset($_SESSION['username'])) {
    header("Location: form_login.php"); // Or wherever your login form is
    exit();
}

// Include database connection
include 'database_con.php'; // Adjust path if needed

// Get database connection
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

// Fetch User ID (Necessary for secure filtering of bookings)
$user_id = null;
$sql = "SELECT User_Id FROM user_details WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_id = $user['User_Id'];
} else {
    // Handle the case where the user ID cannot be retrieved.
    echo "Error: Could not retrieve User ID.";
    exit();  // Or redirect to an error page
}
$stmt->close();

// Fetch Bookings for the logged-in user
$bookings = [];
$booking_sql = "SELECT b.booking_id, b.booking_slot_id, b.engineer_id, 
                    bs.day_id, bs.time, bs.type_id, 
                    d.days, 
                    st.typename, 
                    e.name as engineer_name
                FROM bookings b
                JOIN booking_slot bs ON b.booking_slot_id = bs.booking_slot_id
                JOIN days d ON bs.day_id = d.day_id
                JOIN service_type st ON bs.type_id = st.type_id
                JOIN engineers e ON b.engineer_id = e.engineer_id
                WHERE b.user_id = ?
                ORDER BY bs.day_id, bs.time"; // Order by date and time for display

$booking_stmt = $conn->prepare($booking_sql);
$booking_stmt->bind_param("i", $user_id);
$booking_stmt->execute();
$booking_result = $booking_stmt->get_result();

if ($booking_result->num_rows > 0) {
    while ($row = $booking_result->fetch_assoc()) {
        $bookings[] = $row;
    }
} else {
    $no_bookings_message = "You have no current bookings."; // Message when no bookings found
}

$booking_stmt->close();
$conn->close();

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookings - Rolsa Technologies</title>
    <link rel="stylesheet" href="../CSS/view_bookings.css">
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

    <div class="bookings-container">
    <h1>Your Bookings</h1>

    <!-- Add this right after the <h1>Your Bookings</h1> line -->
    <div class="error-message" style="color: red; padding: 10px; margin-bottom: 15px; background-color: #ffe6e6; border: 1px solid red; border-radius: 5px;" th:if="${session.booking_error_message ne null}" th:text="${session.booking_error_message}">
    </div>

    <div class="success-message" style="color: green; padding: 10px; margin-bottom: 15px; background-color: #e6ffe6; border: 1px solid green; border-radius: 5px;" th:if="${session.booking_success_message ne null}" th:text="${session.booking_success_message}">
    </div>


    <?php if (isset($no_bookings_message)): ?>
    <p class="no-bookings"><?php echo htmlspecialchars($no_bookings_message); ?></p>
    <?php else: ?>
    <table class="booking-table">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Service Type</th>
                <th>Day</th>
                <th>Time</th>
                <th>Engineer</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                <td><?php echo htmlspecialchars($booking['typename']); ?></td>
                <td><?php echo htmlspecialchars($booking['days']); ?></td>
                <td><?php echo htmlspecialchars($booking['time']); ?></td>
                <td><?php echo htmlspecialchars($booking['engineer_name']); ?></td>
                <td class="booking-actions">
                    <a href="edit_booking.php?id=<?php echo htmlspecialchars($booking['booking_id']); ?>" class="btn">Modify</a>
                    <a href="cancel_booking.php?id=<?php echo htmlspecialchars($booking['booking_id']); ?>&csrf_token=<?php echo htmlspecialchars($csrf_token); ?>"
                        class="btn-secondary">Cancel</a>
                    <!-- If you wanted to add a "View Details" link: -->
                    <!-- <a href="booking_details.php?id= echo htmlspecialchars($booking['booking_id']); ?>" class="btn-info">View Details</a> -->
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
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