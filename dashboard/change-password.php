<?php
session_start();
require_once __DIR__ . 
'/../db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $user = getRow("SELECT password_hash FROM users WHERE id = ?", [$user_id]);

    if (!$user || !password_verify($current_password, $user['password_hash'])) {
        $_SESSION['error_message'] = 'Incorrect current password.';
    } elseif ($new_password !== $confirm_password) {
        $_SESSION['error_message'] = 'New password and confirm password do not match.';
    } else {
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = executeQuery("UPDATE users SET password_hash = ? WHERE id = ?", [$new_password_hash, $user_id]);

        if ($stmt) {
            $_SESSION['success_message'] = 'Password changed successfully.';
        } else {
            $_SESSION['error_message'] = 'Failed to change password.';
        }
    }
    header('Location: dashboard.php');
    exit();
}
?>

