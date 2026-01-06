<?php
// submit_booking.php
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

// Use prepared statement for security
$sql = "SELECT * FROM user_details WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $email = $user['email'];
    $user_id = $user['User_Id'];
} else {
    $email = "User data not found";
    $user_id = null;
}
$stmt->close();

// Sanitize input form
function sanitizeInput($data, $type = 'string') {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $conn->real_escape_string($data);
    
    // Additional type-specific sanitation
    if ($type === 'int') {
        return (int)$data;  // Cast to integer
    }
    return $data;
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check session
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        $_SESSION['booking_error_message'] = "Session expired. Please log in again.";
        header("Location: form_login.php");
        exit();
    }

    // Sanitize input
    $userID = sanitizeInput($_SESSION['user_id'], 'int');
    $engineer_id = sanitizeInput($_POST["engineer_id"], 'int');
    $type_id = sanitizeInput($_POST["type_id"], 'int');
    $day_id = sanitizeInput($_POST["day_id"], 'int');
    $time = sanitizeInput($_POST["time"], 'string');

    // FIRST CHECK: Check if this time slot is already booked
    $check_sql = "SELECT b.booking_id FROM booking_slot bs 
                 INNER JOIN bookings b ON bs.booking_slot_id = b.booking_slot_id 
                 WHERE bs.day_id = ? AND bs.time = ? AND b.engineer_id = ?";
    
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("isi", $day_id, $time, $engineer_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // This time slot is already booked
        $_SESSION['booking_error_message'] = "This time slot is already booked. Please select another time.";
        header("Location: book_form.php");
        exit();
    }
    $check_stmt->close();
    
    // SECOND CHECK: Check if user already has a booking at this time
    $user_check_sql = "SELECT b.booking_id FROM booking_slot bs 
                      INNER JOIN bookings b ON bs.booking_slot_id = b.booking_slot_id 
                      WHERE bs.day_id = ? AND bs.time = ? AND b.user_id = ?";
    
    $user_check_stmt = $conn->prepare($user_check_sql);
    $user_check_stmt->bind_param("isi", $day_id, $time, $userID);
    $user_check_stmt->execute();
    $user_check_result = $user_check_stmt->get_result();
    
    if ($user_check_result->num_rows > 0) {
        // User already has a booking at this time
        $_SESSION['booking_error_message'] = "You already have a booking at this time. Please select another time.";
        header("Location: book_form.php");
        exit();
    }
    $user_check_stmt->close();

    // Now create the booking slot since no duplicates were found
    $sql_booking_slot = "INSERT INTO booking_slot (day_id, time, type_id) VALUES (?, ?, ?)";
    $stmt_booking_slot = $conn->prepare($sql_booking_slot);
    $stmt_booking_slot->bind_param("isi", $day_id, $time, $type_id);

    if ($stmt_booking_slot->execute()) {
        $booking_slot_id = $conn->insert_id;
    } else {
        $_SESSION['booking_error_message'] = "There was an error creating the booking slot: " . $stmt_booking_slot->error;
        header("Location: book_form.php");
        exit();
    }
    $stmt_booking_slot->close();

    // Create the booking and allow for engineer change
    $sql_booking = "INSERT INTO bookings (user_id, booking_slot_id, engineer_id) VALUES (?, ?, ?)";
    $stmt_booking = $conn->prepare($sql_booking);
    $stmt_booking->bind_param("iii", $userID, $booking_slot_id, $engineer_id);

    if ($stmt_booking->execute()) {
        $_SESSION['booking_success_message'] = "Appointment booked successfully";
        header("Location: booking_confirmation.php");
        exit();
    } else {
        $_SESSION['booking_error_message'] = "There was an error with the SQL Bookings. " . $stmt_booking->error;
        header("Location: book_form.php");
        exit();
    }
    $stmt_booking->close();
    $conn->close();
} else {
    header("Location: book_form.php");
    exit();
}
?>
