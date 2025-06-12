<?php
require_once __DIR__ . "/../config/database.php";

// Set headers for JSON response
header('Content-Type: application/json');

// Get request method and path
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim(str_replace('/api', '', $path), '/');

// Handle CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($method === 'OPTIONS') {
    exit(0);
}

// Helper functions
function allowedFile($filename) {
    $allowed = ['pdf'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, $allowed);
}

function secureFilename($filename) {
    return preg_replace("/[^a-zA-Z0-9.-]/", "_", $filename);
}

function sendResponse($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

function sendError($message, $status = 400) {
    sendResponse(['error' => $message], $status);
}

// Handle different endpoints
switch ($path) {
    case 'written_materials':
        if ($method === 'GET') {
            // Build query with filters
            $query = "SELECT * FROM written_materials WHERE 1=1";
            $params = [];
            $types = "";
            
            if (isset($_GET['subject'])) {
                $query .= " AND subject = ?";
                $params[] = $_GET['subject'];
                $types .= "s";
            }
            
            if (isset($_GET['topic'])) {
                $query .= " AND topic = ?";
                $params[] = $_GET['topic'];
                $types .= "s";
            }
            
            if (isset($_GET['author'])) {
                $query .= " AND author = ?";
                $params[] = $_GET['author'];
                $types .= "s";
            }
            
            if (isset($_GET['search'])) {
                $search = "%" . $_GET['search'] . "%";
                $query .= " AND (title LIKE ? OR topic LIKE ?)";
                $params[] = $search;
                $params[] = $search;
                $types .= "ss";
            }
            
            $query .= " ORDER BY upload_date DESC";
            
            $stmt = $pdo->prepare($query);
            if (!empty($params)) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            
            $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);
            sendResponse($materials);
        }
        break;

    case 'orals_materials':
        if ($method === 'GET') {
            // Build query with filters
            $query = "SELECT * FROM orals_materials WHERE 1=1";
            $params = [];
            $types = "";
            
            if (isset($_GET['function'])) {
                $query .= " AND function = ?";
                $params[] = $_GET['function'];
                $types .= "s";
            }
            
            if (isset($_GET['topic'])) {
                $query .= " AND topic = ?";
                $params[] = $_GET['topic'];
                $types .= "s";
            }
            
            if (isset($_GET['author'])) {
                $query .= " AND author = ?";
                $params[] = $_GET['author'];
                $types .= "s";
            }
            
            if (isset($_GET['search'])) {
                $search = "%" . $_GET['search'] . "%";
                $query .= " AND (question_type LIKE ? OR topic LIKE ?)";
                $params[] = $search;
                $params[] = $search;
                $types .= "ss";
            }
            
            $query .= " ORDER BY upload_date DESC";
            
            $stmt = $pdo->prepare($query);
            if (!empty($params)) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            
            $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);
            sendResponse($materials);
        }
        break;

    case 'upload_written':
        if ($method === 'POST') {
            if (!isset($_FILES['file'])) {
                sendError('No file provided');
            }
            
            $file = $_FILES['file'];
            if ($file['error'] !== UPLOAD_ERR_OK) {
                sendError('File upload failed');
            }
            
            if (!allowedFile($file['name'])) {
                sendError('Only PDF files are allowed');
            }
            
            // Get form data
            $title = $_POST['title'] ?? '';
            $subject = $_POST['subject'] ?? '';
            $topic = $_POST['topic'] ?? '';
            $author = $_POST['author'] ?? '';
            $pages = (int)($_POST['pages'] ?? 0);
            
            if (empty($title) || empty($subject) || empty($author)) {
                sendError('Title, subject, and author are required');
            }
            
            // Save file
            $filename = secureFilename($file['name']);
            $unique_filename = uniqid() . '_' . $filename;
            $upload_dir = __DIR__ . '/../uploads/';
            $file_path = $upload_dir . $unique_filename;
            
            if (!move_uploaded_file($file['tmp_name'], $file_path)) {
                sendError('Failed to save file');
            }
            
            // Save to database
            $stmt = $pdo->prepare('
                INSERT INTO written_materials (title, subject, topic, author, upload_date, file_path, pages)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ');
            
            $stmt->execute([
                $title,
                $subject,
                $topic,
                $author,
                date('Y-m-d H:i:s'),
                $file_path,
                $pages
            ]);
            
            $material_id = $pdo->lastInsertId();
            
            sendResponse([
                'id' => $material_id,
                'title' => $title,
                'subject' => $subject,
                'topic' => $topic,
                'author' => $author,
                'message' => 'Written material uploaded successfully'
            ], 201);
        }
        break;

    case 'upload_orals':
        if ($method === 'POST') {
            if (!isset($_FILES['file'])) {
                sendError('No file provided');
            }
            
            $file = $_FILES['file'];
            if ($file['error'] !== UPLOAD_ERR_OK) {
                sendError('File upload failed');
            }
            
            if (!allowedFile($file['name'])) {
                sendError('Only PDF files are allowed');
            }
            
            // Get form data
            $question_type = $_POST['question_type'] ?? '';
            $function = $_POST['function'] ?? '';
            $topic = $_POST['topic'] ?? '';
            $author = $_POST['author'] ?? '';
            
            if (empty($question_type) || empty($function) || empty($author)) {
                sendError('Question type, function, and author are required');
            }
            
            // Save file
            $filename = secureFilename($file['name']);
            $unique_filename = uniqid() . '_' . $filename;
            $upload_dir = __DIR__ . '/../uploads/';
            $file_path = $upload_dir . $unique_filename;
            
            if (!move_uploaded_file($file['tmp_name'], $file_path)) {
                sendError('Failed to save file');
            }
            
            // Save to database
            $stmt = $pdo->prepare('
                INSERT INTO orals_materials (question_type, function, topic, author, upload_date, file_path)
                VALUES (?, ?, ?, ?, ?, ?)
            ');
            
            $stmt->execute([
                $question_type,
                $function,
                $topic,
                $author,
                date('Y-m-d H:i:s'),
                $file_path
            ]);
            
            $material_id = $pdo->lastInsertId();
            
            sendResponse([
                'id' => $material_id,
                'question_type' => $question_type,
                'function' => $function,
                'topic' => $topic,
                'author' => $author,
                'message' => 'Orals material uploaded successfully'
            ], 201);
        }
        break;

    case (preg_match('/^written_materials\/(\d+)$/', $path, $matches) ? true : false):
        $material_id = $matches[1];
        
        if ($method === 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data)) {
                sendError('No data provided');
            }
            
            // Build update query
            $update_fields = [];
            $params = [];
            
            foreach (['title', 'subject', 'topic', 'author', 'pages'] as $field) {
                if (isset($data[$field])) {
                    $update_fields[] = "$field = ?";
                    $params[] = $data[$field];
                }
            }
            
            if (empty($update_fields)) {
                sendError('No fields to update');
            }
            
            $params[] = $material_id;
            $query = "UPDATE written_materials SET " . implode(', ', $update_fields) . " WHERE id = ?";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            
            if ($stmt->rowCount() === 0) {
                sendError('Material not found', 404);
            }
            
            sendResponse(['message' => 'Written material updated successfully']);
        }
        elseif ($method === 'DELETE') {
            // Get file path before deletion
            $stmt = $pdo->prepare('SELECT file_path FROM written_materials WHERE id = ?');
            $stmt->execute([$material_id]);
            $material = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$material) {
                sendError('Material not found', 404);
            }
            
            // Delete from database
            $stmt = $pdo->prepare('DELETE FROM written_materials WHERE id = ?');
            $stmt->execute([$material_id]);
            
            // Delete file
            if (file_exists($material['file_path'])) {
                unlink($material['file_path']);
            }
            
            sendResponse(['message' => 'Written material deleted successfully']);
        }
        break;

    case (preg_match('/^orals_materials\/(\d+)$/', $path, $matches) ? true : false):
        $material_id = $matches[1];
        
        if ($method === 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data)) {
                sendError('No data provided');
            }
            
            // Build update query
            $update_fields = [];
            $params = [];
            
            foreach (['question_type', 'function', 'topic', 'author'] as $field) {
                if (isset($data[$field])) {
                    $update_fields[] = "$field = ?";
                    $params[] = $data[$field];
                }
            }
            
            if (empty($update_fields)) {
                sendError('No fields to update');
            }
            
            $params[] = $material_id;
            $query = "UPDATE orals_materials SET " . implode(', ', $update_fields) . " WHERE id = ?";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            
            if ($stmt->rowCount() === 0) {
                sendError('Material not found', 404);
            }
            
            sendResponse(['message' => 'Orals material updated successfully']);
        }
        elseif ($method === 'DELETE') {
            // Get file path before deletion
            $stmt = $pdo->prepare('SELECT file_path FROM orals_materials WHERE id = ?');
            $stmt->execute([$material_id]);
            $material = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$material) {
                sendError('Material not found', 404);
            }
            
            // Delete from database
            $stmt = $pdo->prepare('DELETE FROM orals_materials WHERE id = ?');
            $stmt->execute([$material_id]);
            
            // Delete file
            if (file_exists($material['file_path'])) {
                unlink($material['file_path']);
            }
            
            sendResponse(['message' => 'Orals material deleted successfully']);
        }
        break;

    case (preg_match('/^download\/(.+)$/', $path, $matches) ? true : false):
        $filename = $matches[1];
        $file_path = __DIR__ . '/../uploads/' . $filename;
        
        if (!file_exists($file_path)) {
            sendError('File not found', 404);
        }
        
        // Update download count
        $stmt = $pdo->prepare('UPDATE written_materials SET downloads = downloads + 1 WHERE file_path LIKE ?');
        $stmt->execute(['%' . $filename]);
        
        $stmt = $pdo->prepare('UPDATE orals_materials SET downloads = downloads + 1 WHERE file_path LIKE ?');
        $stmt->execute(['%' . $filename]);
        
        // Send file
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
        break;

    case 'health':
        if ($method === 'GET') {
            sendResponse([
                'status' => 'healthy',
                'message' => 'Study Materials API is running'
            ]);
        }
        break;

    default:
        sendError('Invalid endpoint', 404);
        break;
} 