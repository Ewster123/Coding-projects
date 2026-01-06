<?php
session_start();

// User Authentication Check
if (!isset($_SESSION['username'])) {
    header("Location: form_login.php");
    exit();
}

// Include database connection
include 'database_con.php';

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

// Sanitize booking ID from GET request
$booking_id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

if ($booking_id === false || $booking_id === null) {
    // Invalid booking ID, redirect with an error
    $_SESSION['booking_error_message'] = "Invalid booking ID.";
    header("Location: View_bookings.php");
    exit();
}

// Fetch User ID and Verify Ownership of Booking
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
    $_SESSION['booking_error_message'] = "User not found.";
    header("Location: View_bookings.php");
    exit();
}
$stmt->close();

// Verify that the booking belongs to the logged-in user. This is CRUCIAL.
$verify_sql = "SELECT b.booking_id FROM bookings b WHERE b.booking_id = ? AND b.user_id = ?";
$verify_stmt = $conn->prepare($verify_sql);
$verify_stmt->bind_param("ii", $booking_id, $user_id);
$verify_stmt->execute();
$verify_result = $verify_stmt->get_result();

if ($verify_result->num_rows == 0) {
    // Booking doesn't belong to the user, redirect with an error
    $_SESSION['booking_error_message'] = "You are not authorized to edit this booking.";
    header("Location: View_bookings.php");
    exit();
}
$verify_stmt->close();

// Fetch Booking Details
$booking_sql = "SELECT b.booking_id, b.user_id, b.booking_slot_id, b.engineer_id, 
                    bs.day_id, bs.time, bs.type_id, 
                    d.days, 
                    st.typename, 
                    e.name as engineer_name
                FROM bookings b
                JOIN booking_slot bs ON b.booking_slot_id = bs.booking_slot_id
                JOIN days d ON bs.day_id = d.day_id
                JOIN service_type st ON bs.type_id = st.type_id
                JOIN engineers e ON b.engineer_id = e.engineer_id
                WHERE b.booking_id = ?"; //Only need booking ID

$booking_stmt = $conn->prepare($booking_sql);
$booking_stmt->bind_param("i", $booking_id);
$booking_stmt->execute();
$booking_result = $booking_stmt->get_result();

if ($booking_result->num_rows > 0) {
    $booking = $booking_result->fetch_assoc();
} else {
    $_SESSION['booking_error_message'] = "Booking not found.";
    header("Location: View_bookings.php");
    exit();
}
$booking_stmt->close();

// Fetch Lists of Available Engineers, Days, and Service Types (for the form)
$engineers = [];
$engineer_sql = "SELECT engineer_id, name FROM engineers";
$engineer_result = $conn->query($engineer_sql);
if ($engineer_result->num_rows > 0) {
    while ($row = $engineer_result->fetch_assoc()) {
        $engineers[$row['engineer_id']] = $row['name'];
    }
}

$days = [];
$day_sql = "SELECT day_id, days FROM days";
$day_result = $conn->query($day_sql);
if ($day_result->num_rows > 0) {
    while ($row = $day_result->fetch_assoc()) {
        $days[$row['day_id']] = $row['days'];
    }
}

$service_types = [];
$type_sql = "SELECT type_id, typename FROM service_type";
$type_result = $conn->query($type_sql);
if ($type_result->num_rows > 0) {
    while ($row = $type_result->fetch_assoc()) {
        $service_types[$row['type_id']] = $row['typename'];
    }
}

// Process Form Submission (if any)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Sanitize Data
    $engineer_id = filter_var($_POST["engineer_id"], FILTER_VALIDATE_INT);
    $type_id = filter_var($_POST["type_id"], FILTER_VALIDATE_INT);
    $day_id = filter_var($_POST["day_id"], FILTER_VALIDATE_INT);
    $time = filter_var($_POST["time"], FILTER_SANITIZE_STRING);
    if ($engineer_id === false || $type_id === false || $day_id === false){
        $_SESSION['booking_error_message'] = "Something Went Wrong";
        header("Location: View_bookings.php");
        exit();
    }

    // Check for Date/Time
    $check_sql = "SELECT b.booking_id FROM bookings b
                JOIN booking_slot bs ON b.booking_slot_id = bs.booking_slot_id
                WHERE b.user_id = ? AND bs.day_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $day_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // User already has a booking on this day
        $_SESSION['booking_error_message'] = "You already have a booking on this day. Please choose another day.";
        header("Location: View_bookings.php");
        exit();
    }
    $check_stmt->close();

    // Update Booking Slot Table First
    $update_slot_sql = "UPDATE booking_slot SET day_id = ?, time = ?, type_id = ? WHERE booking_slot_id = ?";
    $update_slot_stmt = $conn->prepare($update_slot_sql);
    $update_slot_stmt->bind_param("isii", $day_id, $time, $type_id, $booking['booking_slot_id']);

    if ($update_slot_stmt->execute()) {
        // Update the Bookings
        $update_booking_sql = "UPDATE bookings SET engineer_id = ? WHERE booking_id = ?";
        $update_booking_stmt = $conn->prepare($update_booking_sql);
        $update_booking_stmt->bind_param("ii", $engineer_id, $booking_id);

        if ($update_booking_stmt->execute()) {
            // Success
            $_SESSION['booking_success_message'] = "Booking updated successfully.";
            header("Location: View_bookings.php"); // Redirect to view bookings page
            exit();
        } else {
            $_SESSION['booking_error_message'] = "Error updating booking: " . $update_booking_stmt->error;
            error_log("Database error updating booking: " . $update_booking_stmt->error);
            header("Location: View_bookings.php");
            exit();
        }
        $update_booking_stmt->close();
    } else {
        $_SESSION['booking_error_message'] = "Error updating booking slot: " . $update_slot_stmt->error;
        error_log("Database error updating booking slot: " . $update_slot_stmt->error);
        header("Location: View_bookings.php");
        exit();
    }
    $update_slot_stmt->close();
}

$conn->close();

// Function to check if the current time value is "AM" or "PM"
function isAMorPM($time) {
    return ($time == "AM" || $time == "PM");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking - Rolsa Technologies</title>
    <link rel="stylesheet" href="../CSS/account.css">
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

    <div class="form_container_row">
        <div class="form-box">
            <h1>Edit Booking</h1>
            <form method="POST" action="">
                <label for="engineer_id">Engineer:</label>
                <select name="engineer_id" id="engineer_id">
                    <?php foreach ($engineers as $id => $name): ?>
                        <option value="<?php echo htmlspecialchars($id); ?>"
                            <?php if ($booking['engineer_id'] == $id) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="type_id">Service Type:</label>
                <select name="type_id" id="type_id">
                    <?php foreach ($service_types as $id => $typename): ?>
                        <option value="<?php echo htmlspecialchars($id); ?>"
                            <?php if ($booking['type_id'] == $id) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($typename); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="day_id">Day:</label>
                <select name="day_id" id="day_id">
                    <?php foreach ($days as $id => $day): ?>
                        <option value="<?php echo htmlspecialchars($id); ?>"
                            <?php if ($booking['day_id'] == $id) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($day); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="time">Select Time (AM/PM):</label>
                <select id="time" name="time" required>
                    <option value="AM" <?php if ($booking['time'] == "AM") echo 'selected'; ?>>AM</option>
                    <option value="PM" <?php if ($booking['time'] == "PM") echo 'selected'; ?>>PM</option>
                </select>

                <button type="submit">Update Booking</button>
            </form>
        </div>
    </div>
</body>
</html>
