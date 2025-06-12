<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

// Get user's enrolled courses
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT c.*, uc.progress, uc.last_accessed
    FROM courses c
    JOIN user_courses uc ON c.id = uc.course_id
    WHERE uc.user_id = ?
    ORDER BY uc.last_accessed DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$enrolled_courses = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get available courses
$stmt = $conn->prepare("
    SELECT c.*
    FROM courses c
    LEFT JOIN user_courses uc ON c.id = uc.course_id AND uc.user_id = ?
    WHERE uc.id IS NULL
    ORDER BY c.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$available_courses = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - User Dashboard - MarineMonks</title>
    <meta name="description" content="Access your enrolled courses and discover new ones on MarineMonks.">
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