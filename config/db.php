<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'marinemonks');

// Create database connection
function getDBConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        return $conn;
    } catch (Exception $e) {
        error_log("Database connection error: " . $e->getMessage());
        return null;
    }
}

// Session configuration
session_start();

// Helper function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Helper function to check if user is admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Helper function to check if user is pro member
function isProMember() {
    return isset($_SESSION['is_pro_member']) && $_SESSION['is_pro_member'] === true;
}

// Helper function to require login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit();
    }
}

// Helper function to require admin
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: /login.php');
        exit();
    }
}

// Helper function to require pro membership
function requireProMembership() {
    if (!isProMember()) {
        header('Location: /pricing.html');
        exit();
    }
}

// Helper function to sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Helper function to handle file uploads
function handleFileUpload($file, $targetDir, $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png']) {
    $fileName = basename($file['name']);
    $targetPath = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
    
    // Check if file type is allowed
    if (!in_array($fileType, $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }
    
    // Check file size (5MB max)
    if ($file['size'] > 5000000) {
        return ['success' => false, 'message' => 'File too large'];
    }
    
    // Generate unique filename
    $fileName = uniqid() . '.' . $fileType;
    $targetPath = $targetDir . $fileName;
    
    // Upload file
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'path' => $targetPath];
    }
    
    return ['success' => false, 'message' => 'Upload failed'];
} 