<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    // Create connection without database
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Read SQL file
    $sql = file_get_contents('database_schema.sql');

    // Execute SQL commands
    $pdo->exec($sql);

    echo "Database and tables created successfully!";
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
} 