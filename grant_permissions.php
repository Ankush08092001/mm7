<?php
// Database configuration
$host = 'localhost';
$user = 'u301363515_rGdKZ';
$pass = ''; // Your cPanel database password
$dbname = 'u301363515_marinemonks';

try {
    // Create connection
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Grant all privileges on the database
    $sql = "GRANT ALL PRIVILEGES ON $dbname.* TO '$user'@'localhost'";
    $pdo->exec($sql);
    
    // Flush privileges
    $pdo->exec("FLUSH PRIVILEGES");

    echo "Permissions granted successfully!";
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
} 