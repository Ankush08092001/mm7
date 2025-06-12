
<?php
session_start();
require_once __DIR__ . 
'/../db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['answersheet_file'])) {
    $user_id = $_SESSION['user_id'];
    $test_id = $_POST['test_id'];
    $upload_dir = '../uploads/answersheets/';
    $file_name = basename($_FILES['answersheet_file']['name']);
    $target_file = $upload_dir . uniqid() . '_' . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is a PDF, DOC, or DOCX
    if ($file_type != 'pdf' && $file_type != 'doc' && $file_type != 'docx') {
        $_SESSION['error_message'] = 'Only PDF, DOC, and DOCX files are allowed.';
        header('Location: dashboard.php');
        exit();
    }

    if (move_uploaded_file($_FILES['answersheet_file']['tmp_name'], $target_file)) {
        // Update answersheets table
        $sql = "UPDATE answersheets SET file_path = ?, status = 'Submitted', submission_date = NOW() WHERE user_id = ? AND test_id = ?";
        $stmt = executeQuery($sql, [$target_file, $user_id, $test_id]);

        if ($stmt) {
            $_SESSION['success_message'] = 'Answer sheet uploaded successfully.';
        } else {
            $_SESSION['error_message'] = 'Failed to update database.';
        }
    } else {
        $_SESSION['error_message'] = 'Failed to upload file.';
    }
}

header('Location: dashboard.php');
exit();
?>

