<?php
session_start();
require_once __DIR__ . "/../config/db.php";

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

$user_id = $_SESSION["user_id"];

try {
    // Get latest 10 notifications
    $stmt = $conn->prepare("
        SELECT id, message, created_at, is_read 
        FROM notifications 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT 10
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $notifications = [];
    while ($row = $result->fetch_assoc()) {
        $notifications[] = [
            'id' => $row['id'],
            'message' => htmlspecialchars($row['message']),
            'created_at' => date('M d, Y H:i', strtotime($row['created_at'])),
            'is_read' => (bool)$row['is_read']
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'notifications' => $notifications
    ]);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch notifications'
    ]);
}

$conn->close();
?> 