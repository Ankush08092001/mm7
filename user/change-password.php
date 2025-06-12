<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate input
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $_SESSION['error'] = 'All fields are required';
        header('Location: /user/dashboard.php');
        exit();
    }

    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = 'New passwords do not match';
        header('Location: /user/dashboard.php');
        exit();
    }

    if (strlen($new_password) < 8) {
        $_SESSION['error'] = 'New password must be at least 8 characters long';
        header('Location: /user/dashboard.php');
        exit();
    }

    // Get current user
    $user = getRow("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
    if (!$user) {
        session_destroy();
        header('Location: /login.php');
        exit();
    }

    // Verify current password
    if (!password_verify($current_password, $user['password_hash'])) {
        $_SESSION['error'] = 'Current password is incorrect';
        header('Location: /user/dashboard.php');
        exit();
    }

    // Hash new password
    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password
    $success = executeQuery(
        "UPDATE users SET password_hash = ? WHERE id = ?",
        [$new_password_hash, $_SESSION['user_id']]
    );

    if ($success) {
        $_SESSION['success'] = 'Password updated successfully';
    } else {
        $_SESSION['error'] = 'Failed to update password';
    }

    header('Location: /user/dashboard.php');
    exit();
} else {
    // If not POST request, redirect to dashboard
    header('Location: /user/dashboard.php');
    exit();
} 