<!-- forgot_password.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../CSS/account.css">
</head>
<body>

    <div class="form_container_row">
        <div class="form-box">
            <h2>Forgot Your Password?</h2>
            <form action="send_reset_email.php" method="post">
                <label for="email">Enter your email: </label>
                <input type="email" id="email" name="email" required>
                <button type="submit">Send Reset Link</button>
            </form>
        </div>
    </div>

</body>
</html>
