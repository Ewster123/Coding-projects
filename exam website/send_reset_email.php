<?php
// forgot_password.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming a connection to the database
    include 'database_con.php';
    
    $email = $_POST['email'];
    
    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Generate a reset token
        $token = bin2hex(random_bytes(16)); // Generate a random token
        
        // Store token and email in the database with an expiration time (e.g., 1 hour)
        $expire_time = date("Y-m-d H:i:s", strtotime('+1 hour'));
        $sql = "INSERT INTO password_resets (email, token, expire_time) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $email, $token, $expire_time);
        $stmt->execute();
        
        // Send email with the reset link
        $reset_link = "http://localhost:3000/PHP/reset_password.php?token=" . $token;
        
        require 'path_to_phpmailer/PHPMailerAutoload.php';
        
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.outlook.com'; // Use your SMTP host
            $mail->SMTPAuth = true;
            $mail->Username = '0020040960-24-892@exams.ccsw.ac.uk'; // Your email address
            $mail->Password = '1xCt3zZ9'; // Your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('your-email@gmail.com', 'Your Website Name');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Click this link to reset your password: <a href='$reset_link'>$reset_link</a>";
            
            $mail->send();
            echo "An email has been sent with instructions to reset your password.";
        } catch (Exception $e) {
            echo "Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email not found!";
    }
}
?>

<form method="POST" action="forgot_password.php">
    <label for="email">Enter your email address:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Send Reset Link</button>
</form>
