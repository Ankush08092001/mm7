<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

// Get user's settings
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM user_settings WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$settings = $result->fetch_assoc();
$stmt->close();

// If no settings exist, create default settings
if (!$settings) {
    $stmt = $conn->prepare("INSERT INTO user_settings (user_id, email_notifications, push_notifications, theme) VALUES (?, 1, 1, 'light')");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    
    $settings = [
        'email_notifications' => 1,
        'push_notifications' => 1,
        'theme' => 'light'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - User Dashboard - MarineMonks</title>
    <meta name="description" content="Manage your MarineMonks account settings and preferences.">
    <link rel="stylesheet" href="/css/consolidated.css">
    <link rel="stylesheet" href="/css/animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon">
</head>
<body>
    <!-- Rest of the file remains unchanged -->
</body>
</html> 