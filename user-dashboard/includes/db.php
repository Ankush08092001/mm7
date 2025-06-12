<?php
require_once __DIR__ . "/../../config/db.php";

// Additional database functions specific to user dashboard
function getStudyMaterials($user_id, $limit = 10) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT sm.*, 
               (SELECT COUNT(*) FROM bookmarks WHERE user_id = ? AND resource_type = 'study_material' AND resource_id = sm.id) as is_bookmarked
        FROM study_materials sm
        ORDER BY sm.created_at DESC
        LIMIT ?
    ");
    $stmt->bind_param("ii", $user_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $materials = [];
    while ($row = $result->fetch_assoc()) {
        $materials[] = $row;
    }
    return $materials;
}

function getMockTests($user_id, $limit = 10) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT mt.*, 
               (SELECT COUNT(*) FROM bookmarks WHERE user_id = ? AND resource_type = 'mock_test' AND resource_id = mt.id) as is_bookmarked,
               e.score, e.feedback
        FROM mock_tests mt
        LEFT JOIN evaluations e ON mt.id = e.test_id
        ORDER BY mt.created_at DESC
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

function getPapers($user_id, $limit = 10) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT p.*, 
               (SELECT COUNT(*) FROM bookmarks WHERE user_id = ? AND resource_type = 'paper' AND resource_id = p.id) as is_bookmarked
        FROM papers p
        ORDER BY p.year DESC
        LIMIT ?
    ");
    $stmt->bind_param("ii", $user_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $papers = [];
    while ($row = $result->fetch_assoc()) {
        $papers[] = $row;
    }
    return $papers;
}

function getProgressData($user_id) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT 
            COUNT(DISTINCT mt.id) as total_tests,
            AVG(e.score) as average_score,
            COUNT(DISTINCT b.id) as total_bookmarks,
            COUNT(DISTINCT CASE WHEN mt.type = 'written' THEN mt.id END) as written_tests,
            COUNT(DISTINCT CASE WHEN mt.type = 'oral' THEN mt.id END) as oral_tests
        FROM users u
        LEFT JOIN mock_tests mt ON u.id = mt.user_id
        LEFT JOIN evaluations e ON mt.id = e.test_id
        LEFT JOIN bookmarks b ON u.id = b.user_id
        WHERE u.id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getTopicProgress($user_id) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT 
            t.name as topic,
            COUNT(DISTINCT mt.id) as tests_attempted,
            AVG(e.score) as average_score
        FROM topics t
        LEFT JOIN mock_tests mt ON t.id = mt.topic_id AND mt.user_id = ?
        LEFT JOIN evaluations e ON mt.id = e.test_id
        GROUP BY t.id, t.name
        ORDER BY t.name
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $topics = [];
    while ($row = $result->fetch_assoc()) {
        $topics[] = $row;
    }
    return $topics;
}
?> 