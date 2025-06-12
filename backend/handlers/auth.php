<?php
function handleAuth($method, $pdo) {
    switch ($method) {
        case 'POST':
            // Get request data
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data)) {
                sendError('No data provided');
            }

            // Validate required fields
            $required_fields = ['username', 'password'];
            foreach ($required_fields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    sendError("Missing required field: $field");
                }
            }

            // Check if user exists
            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
            $stmt->execute([$data['username']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($data['password'], $user['password'])) {
                sendError('Invalid username or password', 401);
            }

            // Start session and set user data
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['subscription_status'] = $user['subscription_status'];

            // Check if user is admin
            $stmt = $pdo->prepare('SELECT id FROM admin_users WHERE user_id = ?');
            $stmt->execute([$user['id']]);
            if ($stmt->fetch()) {
                $_SESSION['admin_id'] = $user['id'];
            }

            sendResponse([
                'message' => 'Login successful',
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'subscription_status' => $user['subscription_status'],
                    'is_admin' => isset($_SESSION['admin_id'])
                ]
            ]);
            break;

        case 'DELETE':
            // Logout user
            session_start();
            session_destroy();
            sendResponse(['message' => 'Logout successful']);
            break;

        default:
            sendError('Method not allowed', 405);
            break;
    }
} 