<?php
require_once '../includes/auth.php';

// Ensure user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    // Get notifications from database
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
    
    echo json_encode(['notifications' => $notifications]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch notifications']);
}
?> 