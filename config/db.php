<?php
$servername = "localhost";
$username = "root"; // Default MySQL username
$password = "password";     // Default MySQL password (empty for root)
$dbname = "marine_monks";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

