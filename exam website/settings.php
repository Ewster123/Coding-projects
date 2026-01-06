<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rolsa Technologies Settings</title>
    <link rel="stylesheet" href="../CSS/Settings.css">
</head>
<body>
<?php
session_start();

// User Authentication Check
if (!isset($_SESSION['username'])) {
    header("Location: form_login.php");
    exit();
}


if (isset($_SESSION['success_message'])) {
    echo '<p class="success-message">' . $_SESSION['success_message'] . '</p>';
    unset($_SESSION['success_message']); // Remove message after displaying
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

$username = $_SESSION['username'];

// Fetch user details (Prepared Statement!)
$sql = "SELECT * FROM user_details WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $email = $user['email'];
    $user_id = $user['User_Id'];
    $_SESSION['user_id'] = $user_id; // Store user ID in session
} else {
    // Handle the case where the user is not found in the database.
    $email = "User data not found";
    $user_id = null;
}
$stmt->close();
    ?>

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

    <div class="settings-container">
        <h1>Account Settings</h1>

        <div class="settings-section">
            <h2>Update Account Information</h2>
            <form action="update_account.php" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password (leave blank to keep current):</label>
                    <input type="password" id="new_password" name="new_password">
                </div>
                <div class="form-group">
                    <button type="submit">Update Information</button>
                </div>
            </form>
        </div>

        <div class="settings-section">
            <h2>Change Password</h2>
            <form action="change_password.php" method="post">
                <div class="form-group">
                    <label for="current_password">Current Password:</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <div class="form-group">
                    <button type="submit">Change Password</button>
                </div>
            </form>
        </div>


        <div class="delete-account-section">
            <h2>Delete Account</h2>
            <p>Warning: This action is irreversible. All your data will be permanently deleted.</p>
            <button onclick="confirmDeleteAccount()">Delete My Account</button>
        </div>
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

         function confirmDeleteAccount() {
            if (confirm("Are you sure you want to delete your account? This action cannot be undone.")) {
                // Redirect to the delete account script
                window.location.href = "delete_account.php";
            }
        }
    </script>
</body>
</html>