<?php
require_once __DIR__ . "/../config/database.php";

// Set headers for JSON response
header('Content-Type: application/json');

// Handle CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Get request method and path
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim(str_replace('/api', '', $path), '/');

// Helper functions
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
    // Study Materials endpoints
    case 'study_materials':
        require_once __DIR__ . '/handlers/study_materials.php';
        handleStudyMaterials($method, $pdo);
        break;

    // Probables endpoints
    case 'probables':
        require_once __DIR__ . '/handlers/probables.php';
        handleProbables($method, $pdo);
        break;

    // Mock Tests endpoints
    case 'mock_tests':
        require_once __DIR__ . '/handlers/mock_tests.php';
        handleMockTests($method, $pdo);
        break;

    // User endpoints
    case 'users':
        require_once __DIR__ . '/handlers/users.php';
        handleUsers($method, $pdo);
        break;

    // Analytics endpoints
    case 'analytics':
        require_once __DIR__ . '/handlers/analytics.php';
        handleAnalytics($method, $pdo);
        break;

    // Settings endpoints
    case 'settings':
        require_once __DIR__ . '/handlers/settings.php';
        handleSettings($method, $pdo);
        break;

    // Auth endpoints
    case 'auth':
        require_once __DIR__ . '/handlers/auth.php';
        handleAuth($method, $pdo);
        break;

    // File handling endpoints
    case (preg_match('/^download\/(.+)$/', $path, $matches) ? true : false):
        require_once __DIR__ . '/handlers/files.php';
        handleFileDownload($matches[1], $pdo);
        break;

    // Health check endpoint
    case 'health':
        sendResponse([
            'status' => 'healthy',
            'message' => 'API is running'
        ]);
        break;

    default:
        sendError('Invalid endpoint', 404);
        break;
}

