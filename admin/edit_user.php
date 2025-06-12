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

// Get user ID from URL
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: users.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate input
    if (empty($username) || empty($email)) {
        $error = 'Username and email are required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } else {
        // Check if username or email already exists (excluding current user)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE (username = ? OR email = ?) AND id != ?");
        $stmt->execute([$username, $email, $user_id]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Username or email already exists';
        } else {
            // Update user
            if (!empty($password)) {
                // Validate password if provided
                if ($password !== $confirm_password) {
                    $error = 'Passwords do not match';
                } elseif (strlen($password) < 8) {
                    $error = 'Password must be at least 8 characters long';
                } else {
                    // Update with new password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("
                        UPDATE users 
                        SET username = ?, email = ?, password = ?, is_active = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([$username, $email, $hashed_password, $is_active, $user_id]);
                }
            } else {
                // Update without changing password
                $stmt = $pdo->prepare("
                    UPDATE users 
                    SET username = ?, email = ?, is_active = ?
                    WHERE id = ?
                ");
                $stmt->execute([$username, $email, $is_active, $user_id]);
            }

            if (!isset($error)) {
                $success = 'User updated successfully';
                // Refresh user data
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $user = $stmt->fetch();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Admin Portal</title>
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
                        <a href="users.php" class="admin-nav-link active">
                            <i class="fas fa-users"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="tests.php" class="admin-nav-link">
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
                <h1 class="admin-header-title">Edit User</h1>
                <div class="admin-header-actions">
                    <a href="users.php" class="admin-btn admin-btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
                </div>
            </header>

            <!-- Edit User Form -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">User Information</h2>
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
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control" 
                               value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" id="password" name="password" class="form-control">
                        <small class="form-text">Password must be at least 8 characters long</small>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <input type="checkbox" name="is_active" <?php echo $user['is_active'] ? 'checked' : ''; ?>>
                            Active Account
                        </label>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="fas fa-save"></i> Update User
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html> 