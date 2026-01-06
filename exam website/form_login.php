<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../CSS/account.css">
</head>
<body>    
    <nav>

    <div class="nav-left">
        <img src="/images/logo2.png" alt="Rolsa Technologies Logo" class="logo">
        </div>

        <ul class="navbar">
            <li><a href="index.php">Home</a></li>
            <li><a href="Shop.php">Shop</a></li>
            <li><a href="About.php">About Us</a></li>
            <li><a href="Green_insights.php">Green Insights</a></li>
            <li class="hamburger-login"><a href="form_login.php">Login</a></li>
            <li class="hamburger-register"><a href="form_register.php">Register</a></li>
        </ul>

        <div class="nav-right">
            <ul class="navbar">
                <li><a href="form_login.php">Login</a></li>
                <li><a href="form_reg.php">Register</a></li>
            </ul>
</div>
        <!--// end of navbar // -->


        <!-- Hamburger Icon for small screens -->
        <div class="hamburger-menu" onclick="toggleMenu()">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </nav>
  
  
  <!-- Registration Form Section -->
  <div class="form_container_row">
        <div class="form-box">
            <form action="login.php" method="post">
                <label for="username">Username: </label>
                <input type="text" placeholder="Username" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" placeholder="Password" id="password" name="password" required>

                <button type="submit">login</button>
            </form>
        </div>
    </div>

    <!-- Accessibility Tab -->
    <div class="accessibility-tab" onclick="toggleAccessibility()">♿</div>
    <div class="accessibility-panel" id="accessibilityPanel">
        <h3>Accessibility Options</h3>
        <button onclick="increaseTextSize()">Increase Text Size</button>
        <button onclick="decreaseTextSize()">Decrease Text Size</button>
        <button onclick="toggleContrast()">Toggle High Contrast</button>
    </div>

    <script>
        function toggleMenu() {
            document.querySelector('.navbar').classList.toggle('active');
        }
        
        function toggleAccessibility() {
            document.getElementById('accessibilityPanel').classList.toggle('active');
        }
        
        // Load saved settings when the page loads
        window.onload = function () {
            let savedTextScale = localStorage.getItem("textScale");
            let savedContrast = localStorage.getItem("highContrast");
        
            if (savedTextScale) {
                document.documentElement.style.setProperty("--text-scale", savedTextScale);
            }
        
            if (savedContrast === "enabled") {
                document.body.classList.add("high-contrast");
            }
        };
        
        // Default text scale
        let textScale = parseFloat(localStorage.getItem("textScale")) || 1;
        
        function increaseTextSize() {
            textScale += 0.2; // Increase scale
            document.documentElement.style.setProperty("--text-scale", textScale);
            localStorage.setItem("textScale", textScale); // Save to localStorage
        }
        
        function decreaseTextSize() {
            textScale = Math.max(0.6, textScale - 0.2); // Prevent going too small
            document.documentElement.style.setProperty("--text-scale", textScale);
            localStorage.setItem("textScale", textScale); // Save to localStorage
        }
        
        function toggleContrast() {
            document.body.classList.toggle("high-contrast");
            
            if (document.body.classList.contains("high-contrast")) {
                localStorage.setItem("highContrast", "enabled");
            } else {
                localStorage.setItem("highContrast", "disabled");
            }
        }
    </script>
    
    <!-- "Don't have an account?" section -->
    <div style="text-align: center; margin-top: 20px;">
        <p>Don't have an account? <a href="form_reg.php">Make one here</a></p>
        
        <a href="forgot_password.php">Forgot Password?</a>
    </div>
    

</body>

</html>
