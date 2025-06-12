<?php
function handleMockTests($method, $pdo) {
    switch ($method) {
        case 'GET':
            // Get filters from query parameters
            $type = $_GET['type'] ?? '';
            $status = $_GET['status'] ?? '';
            $search = $_GET['search'] ?? '';

            // Build query
            $query = "SELECT * FROM mock_tests WHERE 1=1";
            $params = [];
            $types = "";

            if (!empty($type)) {
                $query .= " AND type = ?";
                $params[] = $type;
                $types .= "s";
            }

            if (!empty($status)) {
                $query .= " AND status = ?";
                $params[] = $status;
                $types .= "s";
            }

            if (!empty($search)) {
                $search = "%$search%";
                $query .= " AND title LIKE ?";
                $params[] = $search;
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
            $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);

            sendResponse($tests);
            break;

        case 'POST':
            // Check if user is logged in and is admin
            session_start();
            if (!isset($_SESSION['admin_id'])) {
                sendError('Unauthorized', 401);
            }

            // Validate required fields
            $required_fields = ['title', 'type', 'file'];
            foreach ($required_fields as $field) {
                if (!isset($_POST[$field]) || empty($_POST[$field])) {
                    sendError("Missing required field: $field");
                }
            }

            // Handle file upload
            $file = $_FILES['file'];
            if ($file['error'] !== UPLOAD_ERR_OK) {
                sendError('File upload failed');
            }

            // Validate file type
            $allowed_types = ['pdf'];
            $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($file_ext, $allowed_types)) {
                sendError('Invalid file type. Only PDF files are allowed.');
            }

            // Generate unique filename
            $filename = uniqid() . '_' . basename($file['name']);
            $upload_dir = __DIR__ . '/../../uploads/mock_tests/';
            $file_path = $upload_dir . $filename;

            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $file_path)) {
                sendError('Failed to save file');
            }

            // Insert into database
            $stmt = $pdo->prepare("
                INSERT INTO mock_tests (title, type, file_path, status, created_by)
                VALUES (?, ?, ?, 'draft', ?)
            ");

            $stmt->execute([
                $_POST['title'],
                $_POST['type'],
                $filename,
                $_SESSION['admin_id']
            ]);

            $test_id = $pdo->lastInsertId();

            sendResponse([
                'id' => $test_id,
                'message' => 'Mock test created successfully'
            ], 201);
            break;

        case 'PUT':
            // Check if user is logged in and is admin
            session_start();
            if (!isset($_SESSION['admin_id'])) {
                sendError('Unauthorized', 401);
            }

            // Get test ID from URL
            $test_id = $_GET['id'] ?? null;
            if (!$test_id) {
                sendError('Test ID is required');
            }

            // Get request data
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data)) {
                sendError('No data provided');
            }

            // Build update query
            $update_fields = [];
            $params = [];

            $allowed_fields = ['title', 'type', 'status'];
            foreach ($allowed_fields as $field) {
                if (isset($data[$field])) {
                    $update_fields[] = "$field = ?";
                    $params[] = $data[$field];
                }
            }

            if (empty($update_fields)) {
                sendError('No fields to update');
            }

            $params[] = $test_id;
            $query = "UPDATE mock_tests SET " . implode(', ', $update_fields) . " WHERE id = ?";

            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

            if ($stmt->rowCount() === 0) {
                sendError('Mock test not found', 404);
            }

            sendResponse(['message' => 'Mock test updated successfully']);
            break;

        case 'DELETE':
            // Check if user is logged in and is admin
            session_start();
            if (!isset($_SESSION['admin_id'])) {
                sendError('Unauthorized', 401);
            }

            // Get test ID from URL
            $test_id = $_GET['id'] ?? null;
            if (!$test_id) {
                sendError('Test ID is required');
            }

            // Get file path before deletion
            $stmt = $pdo->prepare('SELECT file_path FROM mock_tests WHERE id = ?');
            $stmt->execute([$test_id]);
            $test = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$test) {
                sendError('Mock test not found', 404);
            }

            // Delete from database
            $stmt = $pdo->prepare('DELETE FROM mock_tests WHERE id = ?');
            $stmt->execute([$test_id]);

            // Delete file
            $file_path = __DIR__ . '/../../uploads/mock_tests/' . $test['file_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            sendResponse(['message' => 'Mock test deleted successfully']);
            break;

        default:
            sendError('Method not allowed', 405);
            break;
    }
} 