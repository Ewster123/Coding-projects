<?php
session_start();
include("database_con.php");

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

// Function to establish database connection
function get_db_connection()
{
    $conn = get_database_connection();
    if (!$conn) {
        die("Database connection failed");
    }
    return $conn;
}

// Function to handle user registration
function register_user($conn, $userData)
{
    $username = $userData["username"];
    $password = password_hash($userData["password"], PASSWORD_DEFAULT);
    $firstname = $userData["firstname"];
    $surname = $userData["surname"];
    $email = $userData["email"];
    $mobile = $userData["mobile"];
    $date_of_birth = $userData["date_of_birth"];
    $currentDate = date("Y/m/d");

    // --- Check if user already exists using prepared statement ---
    $checkUser = "SELECT username FROM user_details WHERE username = ?";
    $stmt = $conn->prepare($checkUser); // Prepare the statement
    
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $username);  // Bind the parameter

    $stmt->execute();

    $result = $stmt->get_result(); // Get the result

    if ($result && $result->num_rows > 0) {
        $_SESSION['error_message'] = "Username already exists. Please choose another.";
        header("Location: form_register.php");
        $stmt->close();
        exit;
    }

    $stmt->close(); // Close the statement

        // --- Insert new user using prepared statement ---
    $sql = "INSERT INTO user_details (username, password, firstname, surname, date_of_birth, email, mobile, date_recorded)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

    if (!$stmt) {
         die("Error preparing statement: " . $conn->error);
    }


    $stmt->bind_param("ssssssss", $username, $password, $firstname, $surname, $date_of_birth, $email, $mobile, $currentDate); // Bind parameters
    
    if ($stmt->execute() === TRUE) {
        $userID = $conn->insert_id;
        $_SESSION['user_id'] = $userID;
        $_SESSION['username'] = $username;
        // Redirect to dashboard.php after successful registration
        header("Location: dashboard.php");
        $stmt->close();
        exit;
    } else {
         $_SESSION['error_message'] = "Error registering user: " . $stmt->error;
        header("Location: form_register.php");
        $stmt->close();
        exit;
    }

}

// Function to get user ID from the session
function get_user_id()
{
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

//Main Function to check if a POST request has been made, and handles the data
function handle_post_request()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = get_db_connection(); // Get the DB connection
        register_user($conn, $_POST); // Handle registration
        $conn->close(); // Close the connection when finished
    }
}

// Call the handle_post_request function
handle_post_request();
?> 