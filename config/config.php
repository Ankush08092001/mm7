<?php
// Site configuration
define('SITE_NAME', 'Marine Monks');
define('SITE_URL', 'https://marinemonks.in');
define('ADMIN_EMAIL', 'admin@marinemonks.in');
define('SUPPORT_EMAIL', 'support@marinemonks.in');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'u301363515_rGdKZ'); // Your cPanel database user
define('DB_PASS', ''); // Your cPanel database password
define('DB_NAME', 'u301363515_marinemonks'); // Your cPanel database name

// SMTP configuration
define('SMTP_HOST', 'smtp.marinemonks.in');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'noreply@marinemonks.in');
define('SMTP_PASSWORD', ''); // Set your SMTP password here
define('SMTP_ENCRYPTION', 'tls');

// File upload configuration
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_FILE_TYPES', ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png']);

// Session configuration
define('SESSION_NAME', 'marinemonks_session');
define('SESSION_LIFETIME', 86400); // 24 hours
define('SESSION_PATH', '/');
define('SESSION_DOMAIN', 'marinemonks.in');
define('SESSION_SECURE', true);
define('SESSION_HTTPONLY', true);

// Security configuration
define('HASH_COST', 12); // For password hashing
define('TOKEN_LIFETIME', 3600); // 1 hour for password reset tokens

// Initialize session with secure settings
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
ini_set('session.cookie_lifetime', SESSION_LIFETIME);

// Set session name
session_name(SESSION_NAME);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error reporting (disable in production)
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Helper functions
function getSiteUrl($path = '') {
    return SITE_URL . '/' . ltrim($path, '/');
}

function getAdminEmail() {
    return ADMIN_EMAIL;
}

function getSupportEmail() {
    return SUPPORT_EMAIL;
}

function isSecure() {
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || $_SERVER['SERVER_PORT'] == 443;
}

function redirect($path) {
    header('Location: ' . getSiteUrl($path));
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('login.php');
    }
}

function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

function requireAdmin() {
    if (!isAdmin()) {
        redirect('login.php');
    }
}

// CSRF Protection
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// XSS Protection
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// File upload validation
function validateFile($file) {
    $errors = [];
    
    if ($file['size'] > MAX_FILE_SIZE) {
        $errors[] = 'File size exceeds limit';
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_FILE_TYPES)) {
        $errors[] = 'File type not allowed';
    }
    
    return $errors;
}

// Email validation
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Password validation
function validatePassword($password) {
    return strlen($password) >= 8
        && preg_match('/[A-Z]/', $password)
        && preg_match('/[a-z]/', $password)
        && preg_match('/[0-9]/', $password);
} 