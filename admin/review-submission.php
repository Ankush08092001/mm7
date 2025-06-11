<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$submission_id = $_GET["id"] ?? null;
$submission = null;
$message = "";

if ($submission_id) {
    $stmt = $conn->prepare("SELECT asheets.id, u.username, mt.test_type, asheets.file_path, asheets.feedback, asheets.status, asheets.marks FROM answersheets asheets JOIN users u ON asheets.user_id = u.id JOIN mock_tests mt ON asheets.test_id = mt.id WHERE asheets.id = ?");
    $stmt->bind_param("i", $submission_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $submission = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $submission_id) {
    $feedback = $_POST["feedback"];
    $marks = $_POST["marks"];
    $status = $_POST["status"];

    $stmt = $conn->prepare("UPDATE answersheets SET feedback = ?, marks = ?, status = ? WHERE id = ?");
    $stmt->bind_param("sisi", $feedback, $marks, $status, $submission_id);

    if ($stmt->execute()) {
        $message = "Submission updated successfully!";
        // Refresh submission data
        $stmt = $conn->prepare("SELECT asheets.id, u.username, mt.test_type, asheets.file_path, asheets.feedback, asheets.status, asheets.marks FROM answersheets asheets JOIN users u ON asheets.user_id = u.id JOIN mock_tests mt ON asheets.test_id = mt.id WHERE asheets.id = ?");
        $stmt->bind_param("i", $submission_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $submission = $result->fetch_assoc();
        $stmt->close();
    } else {
        $message = "Error updating submission: " . $stmt->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Submission - Admin</title>
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
            <h2>Review Mock Test Submission</h2>
            <?php if (!empty($message)): ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>

            <?php if ($submission): ?>
                <div class="submission-details">
                    <p><strong>Submission ID:</strong> <?php echo $submission["id"]; ?></p>
                    <p><strong>User:</strong> <?php echo $submission["username"]; ?></p>
                    <p><strong>Test Type:</strong> <?php echo $submission["test_type"]; ?></p>
                    <p><strong>Status:</strong> <?php echo $submission["status"]; ?></p>
                    <p><strong>Marks:</strong> <?php echo $submission["marks"] ?? "N/A"; ?></p>
                    <p><strong>Answer Sheet:</strong> <a href="../uploads/answersheets/<?php echo $submission["file_path"]; ?>" target="_blank">View Answer Sheet</a></p>
                </div>

                <form action="review-submission.php?id=<?php echo $submission["id"]; ?>" method="post">
                    <div class="form-group">
                        <label for="feedback">Feedback:</label>
                        <textarea id="feedback" name="feedback" rows="10"><?php echo $submission["feedback"]; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="marks">Marks:</label>
                        <input type="number" id="marks" name="marks" value="<?php echo $submission["marks"]; ?>">
                    </div>
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select id="status" name="status">
                            <option value="pending" <?php echo ($submission["status"] == "pending") ? "selected" : ""; ?>>Pending</option>
                            <option value="checked" <?php echo ($submission["status"] == "checked") ? "selected" : ""; ?>>Checked</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Submission</button>
                </form>
            <?php else: ?>
                <p>Submission not found.</p>
            <?php endif; ?>
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

