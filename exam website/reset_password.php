<?php
// reset_password.php

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check the token in the password_resets table
    include 'database_con.php';
    $sql = "SELECT * FROM password_resets WHERE token = ? AND expire_time > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // The token is valid
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = $_POST['password'];
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password

            // Get the email associated with the token
            $row = $result->fetch_assoc();
            $email = $row['email'];

            // Update the user's password
            $sql = "UPDATE users SET password = ? WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $hashed_password, $email);
            $stmt->execute();

            // Delete the reset token from the database as it's no longer needed
            $sql = "DELETE FROM password_resets WHERE token = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $token);
            $stmt->execute();

            echo "Your password has been successfully reset!";
        }
    } else {
        echo "Invalid or expired reset token.";
    }
}
?>

<form method="POST" action="">
    <label for="password">Enter new password:</label>
    <input type="password" id="password" name="password" required>
    <button type="submit">Reset Password</button>
</form>
