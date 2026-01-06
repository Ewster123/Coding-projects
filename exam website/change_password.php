<?php
session_start();

$_SESSION['success_message'] = "Password changed successfully!";
header("Location: settings.php");
exit();

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

$username = $_SESSION['username'];  // Get the logged-in user's username

// Fetch the user's current details from the database (Prepared Statement)
$sql = "SELECT password FROM user_details WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $stored_password = $user['password'];  // This is the hashed password stored in the database
} else {
    $_SESSION['change_password_error'] = "User not found.";
    header("Location: settings.php");
    exit();
}

$stmt->close();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form inputs
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the current password is correct
    if (!password_verify($current_password, $stored_password)) {
        $_SESSION['change_password_error'] = "Current password is incorrect.";
        header("Location: settings.php");
        exit();
    }

    // Check if the new password and confirm password match
    if ($new_password !== $confirm_password) {
        $_SESSION['change_password_error'] = "New password and confirm password do not match.";
        header("Location: settings.php");
        exit();
    }

    // Password strength validation (you can customize this)
    if (strlen($new_password) < 8) {
        $_SESSION['change_password_error'] = "New password must be at least 8 characters long.";
        header("Location: settings.php");
        exit();
    }

    // Hash the new password
    $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $update_sql = "UPDATE user_details SET password = ? WHERE username = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ss", $new_password_hashed, $username);

    if ($update_stmt->execute()) {
        $_SESSION['change_password_success'] = "Password changed successfully.";
        header("Location: settings.php");
    } else {
        $_SESSION['change_password_error'] = "There was an error updating the password.";
        header("Location: settings.php");
    }

    $update_stmt->close();
}

// Close the database connection
$conn->close();
?>
