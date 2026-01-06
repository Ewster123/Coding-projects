<?php
function get_database_connection():mysqli {
    $servername = "ccsw-mysql-";
    $username = "0020040960_24_892_User1";
    $password = "PdC0mQXtv5eJ";
    $database = "0020040960_24_892_DB1";
    $port = 3306;

    // Corrected mysqli connection syntax
    $conn = new mysqli($servername, $username, $password, $database, $port);

    if($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>