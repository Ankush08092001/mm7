<?php
function handleAnalytics($method, $pdo) {
    switch ($method) {
        case 'GET':
            // Check if user is logged in and is admin
            session_start();
            if (!isset($_SESSION['admin_id'])) {
                sendError('Unauthorized', 401);
            }

            // Get filters from query parameters
            $content_type = $_GET['content_type'] ?? '';
            $content_id = $_GET['content_id'] ?? '';
            $start_date = $_GET['start_date'] ?? '';
            $end_date = $_GET['end_date'] ?? '';

            // Build query
            $query = "SELECT * FROM analytics WHERE 1=1";
            $params = [];
            $types = "";

            if (!empty($content_type)) {
                $query .= " AND content_type = ?";
                $params[] = $content_type;
                $types .= "s";
            }

            if (!empty($content_id)) {
                $query .= " AND content_id = ?";
                $params[] = $content_id;
                $types .= "i";
            }

            if (!empty($start_date)) {
                $query .= " AND created_at >= ?";
                $params[] = $start_date;
                $types .= "s";
            }

            if (!empty($end_date)) {
                $query .= " AND created_at <= ?";
                $params[] = $end_date;
                $types .= "s";
            }

            $query .= " ORDER BY created_at DESC";

            // Execute query
            $stmt = $pdo->prepare($query);
            if (!empty($params)) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            $analytics = $stmt->fetchAll(PDO::FETCH_ASSOC);

            sendResponse($analytics);
            break;

        case 'POST':
            // Validate required fields
            $required_fields = ['content_type', 'content_id', 'action_type'];
            foreach ($required_fields as $field) {
                if (!isset($_POST[$field]) || empty($_POST[$field])) {
                    sendError("Missing required field: $field");
                }
            }

            // Get user ID if logged in
            session_start();
            $user_id = $_SESSION['user_id'] ?? null;

            // Insert into database
            $stmt = $pdo->prepare("
                INSERT INTO analytics (content_type, content_id, action_type, user_id)
                VALUES (?, ?, ?, ?)
            ");

            $stmt->execute([
                $_POST['content_type'],
                $_POST['content_id'],
                $_POST['action_type'],
                $user_id
            ]);

            $analytics_id = $pdo->lastInsertId();

            // Update view/download count in respective tables
            $action_type = $_POST['action_type'];
            $content_type = $_POST['content_type'];
            $content_id = $_POST['content_id'];

            if ($action_type === 'view') {
                $count_field = 'view_count';
            } else if ($action_type === 'download') {
                $count_field = 'download_count';
            } else {
                sendError('Invalid action type');
            }

            $table = '';
            switch ($content_type) {
                case 'study_material':
                    $table = 'study_materials';
                    break;
                case 'probable':
                    $table = 'probables';
                    break;
                case 'mock_test':
                    $table = 'mock_tests';
                    break;
                default:
                    sendError('Invalid content type');
            }

            $stmt = $pdo->prepare("UPDATE $table SET $count_field = $count_field + 1 WHERE id = ?");
            $stmt->execute([$content_id]);

            sendResponse([
                'id' => $analytics_id,
                'message' => 'Analytics recorded successfully'
            ], 201);
            break;

        default:
            sendError('Method not allowed', 405);
            break;
    }
} 