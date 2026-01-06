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

// Check if booking ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['booking_error_message'] = "Invalid booking ID.";
    header("Location: View_bookings.php");
    exit();
}

// Verify CSRF token to prevent CSRF attacks
if (!isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['booking_error_message'] = "Security verification failed. Please try again.";
    header("Location: View_bookings.php");
    exit();
}

$booking_id = (int)$_GET['id'];
$username = $_SESSION['username'];

// Get the user ID
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
    $_SESSION['booking_error_message'] = "User authentication failed.";
    header("Location: View_bookings.php");
    exit();
}
$stmt->close();

// Verify the booking belongs to the logged-in user
$check_sql = "SELECT b.booking_id, b.booking_slot_id FROM bookings b WHERE b.booking_id = ? AND b.user_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ii", $booking_id, $user_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows === 0) {
    // Booking doesn't exist or doesn't belong to this user
    $_SESSION['booking_error_message'] = "You don't have permission to cancel this booking.";
    header("Location: View_bookings.php");
    exit();
}

// Get the booking_slot_id for later deletion
$booking = $check_result->fetch_assoc();
$booking_slot_id = $booking['booking_slot_id'];
$check_stmt->close();

// Begin transaction to ensure both deletions succeed or fail together
$conn->begin_transaction();

try {
    // First delete the booking
    $delete_booking_sql = "DELETE FROM bookings WHERE booking_id = ?";
    $delete_booking_stmt = $conn->prepare($delete_booking_sql);
    $delete_booking_stmt->bind_param("i", $booking_id);
    $booking_deleted = $delete_booking_stmt->execute();
    $delete_booking_stmt->close();
    
    if (!$booking_deleted) {
        throw new Exception("Failed to delete booking.");
    }
    
    // Then delete the associated booking slot
    $delete_slot_sql = "DELETE FROM booking_slot WHERE booking_slot_id = ?";
    $delete_slot_stmt = $conn->prepare($delete_slot_sql);
    $delete_slot_stmt->bind_param("i", $booking_slot_id);
    $slot_deleted = $delete_slot_stmt->execute();
    $delete_slot_stmt->close();
    
    if (!$slot_deleted) {
        throw new Exception("Failed to delete booking slot.");
    }
    
    // If both deletions were successful, commit the transaction
    $conn->commit();
    
    // Set success message
    $_SESSION['booking_success_message'] = "Booking cancelled successfully.";
    
} catch (Exception $e) {
    // If there was an error, roll back the transaction
    $conn->rollback();
    
    // Set error message
    $_SESSION['booking_error_message'] = "Error cancelling booking: " . $e->getMessage();
}

// Close the database connection
$conn->close();

// Redirect back to the bookings page
header("Location: View_bookings.php");
exit();
?>