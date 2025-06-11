<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $subject_function = $_POST["subject_function"];
    $topic = $_POST["topic"];
    $author = $_POST["author"];
    $type = $_POST["type"];
    $coming_soon = isset($_POST["coming_soon"]) ? 1 : 0;

    // File upload handling
    $target_dir = "../uploads/study_materials/";
    $file_name = basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $file_name;
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        $message = "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($fileType != "pdf") {
        $message = "Sorry, only PDF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $message = "Sorry, your file was not uploaded. " . $message;
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO study_materials (title, subject_function, topic, author, type, file_path, coming_soon) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssi", $title, $subject_function, $topic, $author, $type, $file_name, $coming_soon);

            if ($stmt->execute()) {
                $message = "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded and data saved.";
            } else {
                $message = "Error saving data to database: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Study Material - Admin</title>
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
            <h2>Upload Study Material</h2>
            <?php if (!empty($message)): ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>
            <form action="upload-study-material.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="type">Material Type:</label>
                    <select id="type" name="type" required>
                        <option value="written">Written</option>
                        <option value="orals">Orals</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="subject_function">Subject/Function:</label>
                    <input type="text" id="subject_function" name="subject_function" required>
                </div>
                <div class="form-group">
                    <label for="topic">Topic:</label>
                    <input type="text" id="topic" name="topic">
                </div>
                <div class="form-group">
                    <label for="author">Author:</label>
                    <input type="text" id="author" name="author">
                </div>
                <div class="form-group">
                    <label for="fileToUpload">Select PDF to upload:</label>
                    <input type="file" name="fileToUpload" id="fileToUpload" required>
                </div>
                <div class="form-group checkbox-group">
                    <input type="checkbox" id="coming_soon" name="coming_soon">
                    <label for="coming_soon">Coming Soon</label>
                </div>
                <button type="submit" class="btn btn-primary">Upload Study Material</button>
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

