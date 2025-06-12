<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

// Get user's bookmarks
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT b.*, 
           CASE 
               WHEN b.type = 'course' THEN c.name
               WHEN b.type = 'material' THEN m.title
               WHEN b.type = 'test' THEN t.name
           END as item_name,
           CASE 
               WHEN b.type = 'course' THEN c.description
               WHEN b.type = 'material' THEN m.description
               WHEN b.type = 'test' THEN t.description
           END as item_description
    FROM bookmarks b
    LEFT JOIN courses c ON b.type = 'course' AND b.item_id = c.id
    LEFT JOIN study_materials m ON b.type = 'material' AND b.item_id = m.id
    LEFT JOIN tests t ON b.type = 'test' AND b.item_id = t.id
    WHERE b.user_id = ?
    ORDER BY b.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$bookmarks = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookmarks - User Dashboard - MarineMonks</title>
    <meta name="description" content="Access your saved bookmarks on MarineMonks.">
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