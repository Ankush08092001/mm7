<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

// Get user's certificates
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT c.*, co.name as course_name
    FROM certificates c
    JOIN courses co ON c.course_id = co.id
    WHERE c.user_id = ?
    ORDER BY c.issued_date DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$certificates = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get user's completed courses without certificates
$stmt = $conn->prepare("
    SELECT c.*
    FROM courses c
    JOIN user_courses uc ON c.id = uc.course_id
    LEFT JOIN certificates cert ON c.id = cert.course_id AND cert.user_id = ?
    WHERE uc.user_id = ? 
    AND uc.progress = 100
    AND cert.id IS NULL
");
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$completed_courses = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificates - User Dashboard - MarineMonks</title>
    <meta name="description" content="View and download your course completion certificates on MarineMonks.">
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