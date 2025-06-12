<?php
function handleProbables($method, $pdo) {
    switch ($method) {
        case 'GET':
            // Get filters from query parameters
            $year = $_GET['year'] ?? '';
            $search = $_GET['search'] ?? '';

            // Build query
            $query = "SELECT * FROM probables WHERE is_coming_soon = FALSE";
            $params = [];
            $types = "";

            if (!empty($year)) {
                $query .= " AND year = ?";
                $params[] = $year;
                $types .= "i";
            }

            if (!empty($search)) {
                $search = "%$search%";
                $query .= " AND title LIKE ?";
                $params[] = $search;
                $types .= "s";
            }

            $query .= " ORDER BY year DESC, created_at DESC";

            // Execute query
            $stmt = $pdo->prepare($query);
            if (!empty($params)) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            $probables = $stmt->fetchAll(PDO::FETCH_ASSOC);

            sendResponse($probables);
            break;

        case 'POST':
            // Check if user is logged in and is admin
            session_start();
            if (!isset($_SESSION['admin_id'])) {
                sendError('Unauthorized', 401);
            }

            // Validate required fields
            $required_fields = ['title', 'year', 'file'];
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
            $upload_dir = __DIR__ . '/../../uploads/probables/';
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
                INSERT INTO probables (title, year, file_path, created_by)
                VALUES (?, ?, ?, ?)
            ");

            $stmt->execute([
                $_POST['title'],
                $_POST['year'],
                $filename,
                $_SESSION['admin_id']
            ]);

            $probable_id = $pdo->lastInsertId();

            sendResponse([
                'id' => $probable_id,
                'message' => 'Probable uploaded successfully'
            ], 201);
            break;

        case 'PUT':
            // Check if user is logged in and is admin
            session_start();
            if (!isset($_SESSION['admin_id'])) {
                sendError('Unauthorized', 401);
            }

            // Get probable ID from URL
            $probable_id = $_GET['id'] ?? null;
            if (!$probable_id) {
                sendError('Probable ID is required');
            }

            // Get request data
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data)) {
                sendError('No data provided');
            }

            // Build update query
            $update_fields = [];
            $params = [];

            $allowed_fields = ['title', 'year', 'is_coming_soon'];
            foreach ($allowed_fields as $field) {
                if (isset($data[$field])) {
                    $update_fields[] = "$field = ?";
                    $params[] = $data[$field];
                }
            }

            if (empty($update_fields)) {
                sendError('No fields to update');
            }

            $params[] = $probable_id;
            $query = "UPDATE probables SET " . implode(', ', $update_fields) . " WHERE id = ?";

            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

            if ($stmt->rowCount() === 0) {
                sendError('Probable not found', 404);
            }

            sendResponse(['message' => 'Probable updated successfully']);
            break;

        case 'DELETE':
            // Check if user is logged in and is admin
            session_start();
            if (!isset($_SESSION['admin_id'])) {
                sendError('Unauthorized', 401);
            }

            // Get probable ID from URL
            $probable_id = $_GET['id'] ?? null;
            if (!$probable_id) {
                sendError('Probable ID is required');
            }

            // Get file path before deletion
            $stmt = $pdo->prepare('SELECT file_path FROM probables WHERE id = ?');
            $stmt->execute([$probable_id]);
            $probable = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$probable) {
                sendError('Probable not found', 404);
            }

            // Delete from database
            $stmt = $pdo->prepare('DELETE FROM probables WHERE id = ?');
            $stmt->execute([$probable_id]);

            // Delete file
            $file_path = __DIR__ . '/../../uploads/probables/' . $probable['file_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            sendResponse(['message' => 'Probable deleted successfully']);
            break;

        default:
            sendError('Method not allowed', 405);
            break;
    }
} 