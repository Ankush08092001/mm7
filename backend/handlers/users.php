<?php
function handleUsers($method, $pdo) {
    switch ($method) {
        case 'GET':
            // Check if user is logged in and is admin
            session_start();
            if (!isset($_SESSION['admin_id'])) {
                sendError('Unauthorized', 401);
            }

            // Get filters from query parameters
            $search = $_GET['search'] ?? '';
            $subscription_status = $_GET['subscription_status'] ?? '';

            // Build query
            $query = "SELECT id, username, email, subscription_status, subscription_end_date, created_at FROM users WHERE 1=1";
            $params = [];
            $types = "";

            if (!empty($search)) {
                $search = "%$search%";
                $query .= " AND (username LIKE ? OR email LIKE ?)";
                $params[] = $search;
                $params[] = $search;
                $types .= "ss";
            }

            if (!empty($subscription_status)) {
                $query .= " AND subscription_status = ?";
                $params[] = $subscription_status;
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
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            sendResponse($users);
            break;

        case 'POST':
            // Validate required fields
            $required_fields = ['username', 'email', 'password'];
            foreach ($required_fields as $field) {
                if (!isset($_POST[$field]) || empty($_POST[$field])) {
                    sendError("Missing required field: $field");
                }
            }

            // Validate email format
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                sendError('Invalid email format');
            }

            // Check if username or email already exists
            $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
            $stmt->execute([$_POST['username'], $_POST['email']]);
            if ($stmt->fetch()) {
                sendError('Username or email already exists');
            }

            // Hash password
            $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Insert into database
            $stmt = $pdo->prepare("
                INSERT INTO users (username, email, password, subscription_status)
                VALUES (?, ?, ?, 'free')
            ");

            $stmt->execute([
                $_POST['username'],
                $_POST['email'],
                $hashed_password
            ]);

            $user_id = $pdo->lastInsertId();

            sendResponse([
                'id' => $user_id,
                'message' => 'User registered successfully'
            ], 201);
            break;

        case 'PUT':
            // Check if user is logged in
            session_start();
            if (!isset($_SESSION['user_id'])) {
                sendError('Unauthorized', 401);
            }

            // Get request data
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data)) {
                sendError('No data provided');
            }

            // Build update query
            $update_fields = [];
            $params = [];

            $allowed_fields = ['email', 'password'];
            foreach ($allowed_fields as $field) {
                if (isset($data[$field])) {
                    if ($field === 'password') {
                        $data[$field] = password_hash($data[$field], PASSWORD_DEFAULT);
                    }
                    $update_fields[] = "$field = ?";
                    $params[] = $data[$field];
                }
            }

            if (empty($update_fields)) {
                sendError('No fields to update');
            }

            $params[] = $_SESSION['user_id'];
            $query = "UPDATE users SET " . implode(', ', $update_fields) . " WHERE id = ?";

            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

            if ($stmt->rowCount() === 0) {
                sendError('User not found', 404);
            }

            sendResponse(['message' => 'Profile updated successfully']);
            break;

        case 'DELETE':
            // Check if user is logged in and is admin
            session_start();
            if (!isset($_SESSION['admin_id'])) {
                sendError('Unauthorized', 401);
            }

            // Get user ID from URL
            $user_id = $_GET['id'] ?? null;
            if (!$user_id) {
                sendError('User ID is required');
            }

            // Delete from database
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$user_id]);

            if ($stmt->rowCount() === 0) {
                sendError('User not found', 404);
            }

            sendResponse(['message' => 'User deleted successfully']);
            break;

        default:
            sendError('Method not allowed', 405);
            break;
    }
} 