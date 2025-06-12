<?php
require_once '../includes/auth.php';

// Ensure user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$notification_id = isset($input['notification_id']) ? (int)$input['notification_id'] : 0;

if (!$notification_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid notification ID']);
    exit;
}

try {
    // Verify notification belongs to user and mark as read
    $stmt = $conn->prepare("
        UPDATE notifications 
        SET is_read = 1 
        WHERE id = ? AND user_id = ?
    ");
    
    $stmt->bind_param("ii", $notification_id, $user_id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Notification not found']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to mark notification as read']);
}
?> 