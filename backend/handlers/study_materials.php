<?php
function handleStudyMaterials($method, $pdo) {
    switch ($method) {
        case 'GET':
            // Get filters from query parameters
            $type = $_GET['type'] ?? 'written';
            $subject = $_GET['subject'] ?? '';
            $function = $_GET['function'] ?? '';
            $topic = $_GET['topic'] ?? '';
            $author = $_GET['author'] ?? '';
            $search = $_GET['search'] ?? '';

            // Build query
            $query = "SELECT * FROM study_materials WHERE type = ? AND is_coming_soon = FALSE";
            $params = [$type];
            $types = "s";

            if ($type === 'written' && !empty($subject)) {
                $query .= " AND subject = ?";
                $params[] = $subject;
                $types .= "s";
            } elseif ($type === 'orals' && !empty($function)) {
                $query .= " AND function = ?";
                $params[] = $function;
                $types .= "s";
            }

            if (!empty($topic)) {
                $query .= " AND topic = ?";
                $params[] = $topic;
                $types .= "s";
            }

            if (!empty($author)) {
                $query .= " AND author = ?";
                $params[] = $author;
                $types .= "s";
            }

            if (!empty($search)) {
                $search = "%$search%";
                $query .= " AND (title LIKE ? OR topic LIKE ?)";
                $params[] = $search;
                $params[] = $search;
                $types .= "ss";
            }

            $query .= " ORDER BY created_at DESC";

            // Execute query
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

            sendResponse($materials);
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
            $upload_dir = __DIR__ . '/../../uploads/study_materials/';
            $file_path = $upload_dir . $filename;

            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $file_path)) {
                sendError('Failed to save file');
            }

            // Prepare data for insertion
            $data = [
                'title' => $_POST['title'],
                'type' => $_POST['type'],
                'subject' => $_POST['subject'] ?? null,
                'function' => $_POST['function'] ?? null,
                'topic' => $_POST['topic'] ?? null,
                'author' => $_POST['author'] ?? null,
                'file_path' => $filename,
                'is_pro_only' => isset($_POST['is_pro_only']) ? 1 : 0,
                'created_by' => $_SESSION['admin_id']
            ];

            // Insert into database
            $stmt = $pdo->prepare("
                INSERT INTO study_materials 
                (title, type, subject, function, topic, author, file_path, is_pro_only, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $data['title'],
                $data['type'],
                $data['subject'],
                $data['function'],
                $data['topic'],
                $data['author'],
                $data['file_path'],
                $data['is_pro_only'],
                $data['created_by']
            ]);

            $material_id = $pdo->lastInsertId();

            sendResponse([
                'id' => $material_id,
                'message' => 'Study material uploaded successfully'
            ], 201);
            break;

        case 'PUT':
            // Check if user is logged in and is admin
            session_start();
            if (!isset($_SESSION['admin_id'])) {
                sendError('Unauthorized', 401);
            }

            // Get material ID from URL
            $material_id = $_GET['id'] ?? null;
            if (!$material_id) {
                sendError('Material ID is required');
            }

            // Get request data
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data)) {
                sendError('No data provided');
            }

            // Build update query
            $update_fields = [];
            $params = [];

            $allowed_fields = ['title', 'subject', 'function', 'topic', 'author', 'is_pro_only'];
            foreach ($allowed_fields as $field) {
                if (isset($data[$field])) {
                    $update_fields[] = "$field = ?";
                    $params[] = $data[$field];
                }
            }

            if (empty($update_fields)) {
                sendError('No fields to update');
            }

            $params[] = $material_id;
            $query = "UPDATE study_materials SET " . implode(', ', $update_fields) . " WHERE id = ?";

            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

            if ($stmt->rowCount() === 0) {
                sendError('Material not found', 404);
            }

            sendResponse(['message' => 'Study material updated successfully']);
            break;

        case 'DELETE':
            // Check if user is logged in and is admin
            session_start();
            if (!isset($_SESSION['admin_id'])) {
                sendError('Unauthorized', 401);
            }

            // Get material ID from URL
            $material_id = $_GET['id'] ?? null;
            if (!$material_id) {
                sendError('Material ID is required');
            }

            // Get file path before deletion
            $stmt = $pdo->prepare('SELECT file_path FROM study_materials WHERE id = ?');
            $stmt->execute([$material_id]);
            $material = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$material) {
                sendError('Material not found', 404);
            }

            // Delete from database
            $stmt = $pdo->prepare('DELETE FROM study_materials WHERE id = ?');
            $stmt->execute([$material_id]);

            // Delete file
            $file_path = __DIR__ . '/../../uploads/study_materials/' . $material['file_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            sendResponse(['message' => 'Study material deleted successfully']);
            break;

        default:
            sendError('Method not allowed', 405);
            break;
    }
} 