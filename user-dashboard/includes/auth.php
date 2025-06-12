<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION["user_id"]);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: ../login.php");
        exit();
    }
}

function getUserData() {
    global $conn;
    if (!isLoggedIn()) return null;
    
    $user_id = $_SESSION["user_id"];
    $stmt = $conn->prepare("SELECT id, name, email, membership_tier, profile_photo, last_login FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function updateLastLogin() {
    global $conn;
    if (!isLoggedIn()) return;
    
    $user_id = $_SESSION["user_id"];
    $stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}

function getNotificationsCount() {
    global $conn;
    if (!isLoggedIn()) return 0;
    
    $user_id = $_SESSION["user_id"];
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['count'];
}

function getRecentTests($limit = 5) {
    global $conn;
    if (!isLoggedIn()) return [];
    
    $user_id = $_SESSION["user_id"];
    $stmt = $conn->prepare("
        SELECT mt.*, e.score, e.feedback 
        FROM mock_tests mt 
        LEFT JOIN evaluations e ON mt.id = e.test_id 
        WHERE mt.user_id = ? 
        ORDER BY mt.submitted_at DESC 
        LIMIT ?
    ");
    $stmt->bind_param("ii", $user_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $tests = [];
    while ($row = $result->fetch_assoc()) {
        $tests[] = $row;
    }
    return $tests;
}

function getRecentBookmarks($limit = 5) {
    global $conn;
    if (!isLoggedIn()) return [];
    
    $user_id = $_SESSION["user_id"];
    $stmt = $conn->prepare("
        SELECT b.*, 
               CASE 
                   WHEN b.resource_type = 'study_material' THEN sm.title
                   WHEN b.resource_type = 'mock_test' THEN mt.title
                   WHEN b.resource_type = 'paper' THEN p.title
               END as title
        FROM bookmarks b
        LEFT JOIN study_materials sm ON b.resource_type = 'study_material' AND b.resource_id = sm.id
        LEFT JOIN mock_tests mt ON b.resource_type = 'mock_test' AND b.resource_id = mt.id
        LEFT JOIN papers p ON b.resource_type = 'paper' AND b.resource_id = p.id
        WHERE b.user_id = ?
        ORDER BY b.created_at DESC
        LIMIT ?
    ");
    $stmt->bind_param("ii", $user_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $bookmarks = [];
    while ($row = $result->fetch_assoc()) {
        $bookmarks[] = $row;
    }
    return $bookmarks;
}
?> 