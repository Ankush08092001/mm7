<?php
function handleSettings($method, $pdo) {
    switch ($method) {
        case 'GET':
            // Get all settings
            $stmt = $pdo->query('SELECT * FROM settings');
            $settings = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Convert to key-value pairs
            $settings_array = [];
            foreach ($settings as $setting) {
                $settings_array[$setting['key']] = $setting['value'];
            }

            sendResponse($settings_array);
            break;

        case 'PUT':
            // Check if user is logged in and is admin
            session_start();
            if (!isset($_SESSION['admin_id'])) {
                sendError('Unauthorized', 401);
            }

            // Get request data
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data)) {
                sendError('No data provided');
            }

            // Update settings
            $stmt = $pdo->prepare('UPDATE settings SET value = ? WHERE `key` = ?');
            foreach ($data as $key => $value) {
                $stmt->execute([$value, $key]);
            }

            sendResponse(['message' => 'Settings updated successfully']);
            break;

        default:
            sendError('Method not allowed', 405);
            break;
    }
} 