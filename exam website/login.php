<?php
session_start();
include 'database_con.php';

$conn = get_database_connection(); // gets the connection details from database_con.php

// Function to sanitize input
function sanitize_input($conn, $input) {
    return $conn->real_escape_string($input);
}

// Function to fetch user details by username
function get_user_by_username($conn, $username) {
    $sql = "SELECT * FROM user_details WHERE username = ?"; // SQL query to select user details by username
    $stmt = $conn->prepare($sql); // Prepares the SQL query to prevent SQL injection

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error); // If the query preparation fails
    }

    $stmt->bind_param("s", $username); // Bind the username to the SQL query
    $stmt->execute(); // Execute the query

    return $stmt->get_result(); // Return the result of the query
}

// Function to handle user login
function handle_user_login($conn) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize input
        $username = sanitize_input($conn, $_POST["username"]);
        $password = sanitize_input($conn, $_POST["password"]);

        header ("dashboard.php");

        // Fetch user details from the database
        $result = get_user_by_username($conn, $username);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc(); // Fetch the row of the user

            // Verify the password
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['user_id']; // Set session user ID
                $_SESSION['username'] = $username;  // Set session username

                header("Location: dashboard.php"); // Redirect to dashboard
                exit();
            } else {
                echo "Invalid username or password"; // If password is incorrect
            }
        } else {
            echo "Invalid username or password"; // If user doesn't exist
        }
    } else {
        echo "Invalid request method."; // If request method is not POST
    }
}

// Call the handle_user_login function
handle_user_login($conn);

// Close the database connection
$conn->close();
?>
