
<?php
session_start();
require_once __DIR__ . 
'/../db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// In a real application, you would ask for password confirmation before deletion.
// For this example, we'll proceed directly.

$stmt = executeQuery("DELETE FROM users WHERE id = ?", [$user_id]);

if ($stmt) {
    session_destroy();
    header('Location: ../signup.php'); // Redirect to signup or homepage after deletion
    exit();
} else {
    $_SESSION['error_message'] = 'Failed to delete account.';
    header('Location: dashboard.php');
    exit();
}
?>

