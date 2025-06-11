<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $test_type = $_POST["test_type"];
    $questions = $_POST["questions"];

    // File upload handling (optional for mock tests, as questions can be text)
    $upload_path = null;
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
        $target_dir = "../uploads/mock_tests/";
        $file_name = basename($_FILES["fileToUpload"]["name"]);
        $target_file = $target_dir . $file_name;
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allow certain file formats
        if($fileType != "pdf" && $fileType != "doc" && $fileType != "docx") {
            $message = "Sorry, only PDF, DOC, DOCX files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            $message = "Sorry, your file was not uploaded. " . $message;
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $upload_path = $file_name;
            } else {
                $message = "Sorry, there was an error uploading your file.";
            }
        }
    }

    if ($upload_path || !empty($questions)) {
        $stmt = $conn->prepare("INSERT INTO mock_tests (test_type, questions, upload_path) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $test_type, $questions, $upload_path);

        if ($stmt->execute()) {
            $message = "Mock test uploaded and data saved.";
        } else {
            $message = "Error saving data to database: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Please provide questions or upload a file.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Mock Test - Admin</title>
    <link rel="stylesheet" href="../css/consolidated.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <div class="container">
                <a href="../index.php" class="logo">MarineMonks</a>
                <ul class="nav-links">
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../study-material.php">Study Material</a></li>
                    <li><a href="../mock-tests.php">Mock Tests</a></li>
                    <li><a href="../papers.html">Papers</a></li>
                    <li><a href="../probables.php">Probables</a></li>
                    <li><a href="../logout.php" class="btn btn-outline">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="admin-dashboard">
        <div class="container">
            <h2>Upload Mock Test</h2>
            <?php if (!empty($message)): ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>
            <form action="upload-mock-test.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="test_type">Test Type:</label>
                    <select id="test_type" name="test_type" required>
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="questions">Questions (Text or Upload File):</label>
                    <textarea id="questions" name="questions" rows="10" placeholder="Enter questions here..."></textarea>
                </div>
                <div class="form-group">
                    <label for="fileToUpload">Upload Questions File (PDF/DOC/DOCX):</label>
                    <input type="file" name="fileToUpload" id="fileToUpload">
                </div>
                <button type="submit" class="btn btn-primary">Upload Mock Test</button>
            </form>
            <p><a href="index.php">Back to Admin Dashboard</a></p>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 MarineMonks. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

