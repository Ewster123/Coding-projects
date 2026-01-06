<?php 
session_start();

// Function to check if the user is logged in
function isLoggedIn()
{
    return isset($_SESSION['username']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Insights</title>
    <link rel="stylesheet" href="../CSS/green_insights.css">

    <script src="../javascript/accessibility.js" defer></script>
    <script src="../javascript/navigation.js" defer></script>
    <script src="../javascript/as.js" defer></script>
    <script src="../javascript/asopenclose.js" defer></script>
    <script src="../javascript/Carbon_calc.js" defer></script>
    <script src="../javascript/initialisation.js" defer></script>

</head>

<nav>
        <div class="nav-left">
            <img src="../images/logo2.png" alt="Rolsa Technologies Logo" class="logo">
        </div>

        <ul class="navbar">
        <?php if (isLoggedIn()) { ?>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="index.php">Home</a></li>
                <li><a href="Shop.php">Shop</a></li>
                <li><a href="About.php">About Us</a></li>
                <li><a href="Green_insights.php">Green Insights</a></li>
                <li class="hamburger-login"><a href="logout.php">Logout</a></li>
            </ul>
            <div class="nav-right">
                <ul>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
            <?php } else { ?>
                <li><a href="index.php">Home</a></li>
                <li><a href="Shop.php">Shop</a></li>
                <li><a href="About.php">About Us</a></li>
                <li><a href="Green_insights.php">Green Insights</a></li>
                <li class="hamburger-login"><a href="form_login">Login</a></li>
                <li class="hamburger-register"><a href="form_register">Register</a></li>
            </ul>
            <div class="nav-right">
                <ul class="navbar">
                    <li><a href="form_login.php">Login</a></li>
                    <li><a href="form_reg.php">Register</a></li>
                </ul>
            </div>
            <?php } ?>


        <div class="hamburger-menu" onclick="toggleMenu()">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </nav>

    <div class="hero3">
        <div class="hero-text">
            <h1>Reduce Your Emissions with Solar Power</h1>
            <p>Learn how solar energy can help you reduce your carbon footprint and contribute to a sustainable
                future.</p>
            <b>The Impact of Emissions:</b><br>
            <p>Emissions from traditional energy sources contribute to climate change, air pollution, and other
                environmental problems. Switching to renewable energy sources like solar power can significantly
                reduce these harmful effects.</p>
            <p><strong>Benefits of Solar Power:</strong></p>
            <ul>
                <li>Reduces reliance on fossil fuels</li>
                <li>Lowers carbon emissions</li>
                <li>Decreases air pollution</li>
                <li>Contributes to a cleaner environment</li>
                <li>Offers long-term cost savings</li>
            </ul>
            <a href="Shop.html">Explore Our Solar Panel Solutions</a>
        </div>

        <!-- Carbon Footprint Calculator Section -->
        <div class="calculator-container">
            <h2>Carbon Footprint Calculator</h2>

            <div class="calculator-form">
                <div class="input-group">
                    <label for="energy">Annual Energy Consumption</label>
                    <input type="number" id="energy" value="12000" min="1000" max="50000">
                    <input type="range" id="energySlider" min="1000" max="50000" value="12000">
                    <div class="range-labels">
                        <span>1,000 kWh</span>
                        <span>50,000 kWh</span>
                    </div>
                    <small>Enter your total energy consumption for the year in kWh</small>
                </div>

                <div class="input-group">
                    <label for="mileage">Annual Transportation Mileage</label>
                    <input type="number" id="mileage" value="15000" min="1000" max="50000">
                    <input type="range" id="mileageSlider" min="1000" max="50000" value="15000">
                    <div class="range-labels">
                        <span>1,000 miles</span>
                        <span>50,000 miles</span>
                    </div>
                    <small>Enter your total annual mileage from all modes of transport</small>
                </div>

                <div class="input-group">
                    <label for="waste">Annual Waste Production</label>
                    <input type="number" id="waste" value="4.6" min="0.1" max="10" step="0.1">
                    <input type="range" id="wasteSlider" min="0.1" max="10" value="4.6" step="0.1">
                    <div class="range-labels">
                        <span>0.1 tons</span>
                        <span>10 tons</span>
                    </div>
                    <small>Enter your total waste production for the year in tons</small>
                </div>

                <div class="input-group">
                    <label>Primary Energy Source</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="grid" name="energy-source" value="grid" checked>
                            <label for="grid">Electricity from Grid</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="gas" name="energy-source" value="gas">
                            <label for="gas">Natural Gas</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="solar" name="energy-source" value="solar">
                            <label for="solar">Solar</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="wind" name="energy-source" value="wind">
                            <label for="wind">Wind</label>
                        </div>
                    </div>
                </div>

                <button id="calculateBtn">Calculate Footprint</button>

                <div id="result">
                    <h3>Total Annual Carbon Footprint</h3>
                    <div id="totalFootprint">11,046.98 tons CO2e</div>
                    <p>Total annual carbon footprint based on your input.</p>

                    <div class="result-item">
                        <div class="result-value" id="energyEmissions">11,040.00 tons CO2e</div>
                        <div class="result-description">Carbon emissions from energy consumption.</div>
                    </div>

                    <div class="result-item">
                        <div class="result-value" id="transportEmissions">6.06 tons CO2e</div>
                        <div class="result-description">Carbon emissions from transportation.</div>
                    </div>

                    <div class="result-item">
                        <div class="result-value" id="wasteEmissions">0.92 tons CO2e</div>
                        <div class="result-description">Carbon emissions from waste production.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- end of carbon footprint calc code -->

    </div>

    <div class="container-row">

        <a href="index.html" class="box">
            <img src="../images/Solar_installation2.jpg" alt="Green energy insights">
            <p>Home</p>
        </a>
        <a href="Shop.html" class="box">
            <img src="../images/ev_shop.jpg" alt="ROlSA Technologies EV shop">
            <p>EV Shop</p>
        </a>
        <a href="About.html" class="box">
            <img src="../images/about_rolsa.jpg" alt="About ROLSA Technologies">
            <p>About Us</p>
        </a>

    </div>

    <!-- Accessibility Tab -->
    <div class="accessibility-tab" onclick="toggleAccessibility()">♿</div>
    <div class="accessibility-panel" id="accessibilityPanel">
        <h3>Accessibility Options</h3>
        <button onclick="increaseTextSize()">Increase Text Size</button>
        <button onclick="decreaseTextSize()">Decrease Text Size</button>
        <button onclick="toggleContrast()">Toggle High Contrast</button>
    </div>
</body>

<footer>
    <p>© 2025 All Rights Reserved. Designed by Ewan Evans.</p>
</footer>

</html>
