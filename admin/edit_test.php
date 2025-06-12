<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Include database configuration
require_once '../config/database.php';

$error = '';
$success = '';

// Get test ID from URL
$test_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get test data
$stmt = $pdo->prepare("SELECT * FROM mock_tests WHERE id = ?");
$stmt->execute([$test_id]);
$test = $stmt->fetch();

if (!$test) {
    header('Location: tests.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $duration = (int)($_POST['duration'] ?? 0);
    $passing_score = (int)($_POST['passing_score'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Validate input
    if (empty($title)) {
        $error = 'Test title is required';
    } elseif ($duration < 1) {
        $error = 'Duration must be at least 1 minute';
    } elseif ($passing_score < 0 || $passing_score > 100) {
        $error = 'Passing score must be between 0 and 100';
    } else {
        // Update test
        $stmt = $pdo->prepare("
            UPDATE mock_tests 
            SET title = ?, description = ?, duration = ?, passing_score = ?, is_active = ?
            WHERE id = ?
        ");
        
        if ($stmt->execute([$title, $description, $duration, $passing_score, $is_active, $test_id])) {
            $success = 'Test updated successfully';
            // Refresh test data
            $stmt = $pdo->prepare("SELECT * FROM mock_tests WHERE id = ?");
            $stmt->execute([$test_id]);
            $test = $stmt->fetch();
        } else {
            $error = 'Failed to update test';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mock Test - Admin Portal</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <img src="../assets/images/logo.png" alt="Logo">
                <span>Admin Portal</span>
            </div>
            <nav>
                <ul class="admin-nav">
                    <li class="admin-nav-item">
                        <a href="index.php" class="admin-nav-link">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="users.php" class="admin-nav-link">
                            <i class="fas fa-users"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="tests.php" class="admin-nav-link active">
                            <i class="fas fa-file-alt"></i>
                            <span>Mock Tests</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="questions.php" class="admin-nav-link">
                            <i class="fas fa-question-circle"></i>
                            <span>Questions</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="results.php" class="admin-nav-link">
                            <i class="fas fa-chart-bar"></i>
                            <span>Results</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="settings.php" class="admin-nav-link">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-content">
            <header class="admin-header">
                <h1 class="admin-header-title">Edit Mock Test</h1>
                <div class="admin-header-actions">
                    <a href="tests.php" class="admin-btn admin-btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Tests
                    </a>
                </div>
            </header>

            <!-- Edit Test Form -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Test Information</h2>
                </div>
                
                <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>

                <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
                <?php endif; ?>

                <form method="POST" class="admin-form">
                    <div class="form-group">
                        <label for="title" class="form-label">Test Title</label>
                        <input type="text" id="title" name="title" class="form-control" 
                               value="<?php echo htmlspecialchars($test['title']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="4"><?php echo htmlspecialchars($test['description']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="duration" class="form-label">Duration (minutes)</label>
                        <input type="number" id="duration" name="duration" class="form-control" 
                               value="<?php echo htmlspecialchars($test['duration']); ?>" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="passing_score" class="form-label">Passing Score (%)</label>
                        <input type="number" id="passing_score" name="passing_score" class="form-control" 
                               value="<?php echo htmlspecialchars($test['passing_score']); ?>" min="0" max="100" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <input type="checkbox" name="is_active" <?php echo $test['is_active'] ? 'checked' : ''; ?>>
                            Active Test
                        </label>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="fas fa-save"></i> Update Test
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html> 