<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Include database configuration
require_once '../config/database.php';

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'delete':
                if (isset($_POST['user_id'])) {
                    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                    $stmt->execute([$_POST['user_id']]);
                }
                break;
            
            case 'toggle_status':
                if (isset($_POST['user_id'])) {
                    $stmt = $pdo->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?");
                    $stmt->execute([$_POST['user_id']]);
                }
                break;
        }
    }
}

// Get users with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Get total users count
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_pages = ceil($total_users / $per_page);

// Get users for current page
$stmt = $pdo->prepare("
    SELECT u.*, 
           COUNT(DISTINCT tr.id) as total_tests,
           COUNT(DISTINCT tr.id) FILTER (WHERE tr.score >= 60) as passed_tests
    FROM users u
    LEFT JOIN test_results tr ON u.id = tr.user_id
    GROUP BY u.id
    ORDER BY u.created_at DESC
    LIMIT ? OFFSET ?
");
$stmt->execute([$per_page, $offset]);
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Admin Portal</title>
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
                <h1 class="admin-header-title">User Management</h1>
                <div class="admin-header-actions">
                    <a href="add_user.php" class="admin-btn admin-btn-primary">
                        <i class="fas fa-plus"></i> Add User
                    </a>
                </div>
            </header>

            <!-- Users Table -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">All Users</h2>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Tests Taken</th>
                            <th>Passed Tests</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo $user['total_tests']; ?></td>
                            <td><?php echo $user['passed_tests']; ?></td>
                            <td>
                                <span class="status-badge <?php echo $user['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                    <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="toggle_status">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" class="admin-btn admin-btn-secondary">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                </form>
                                <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="admin-btn admin-btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" class="admin-btn admin-btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>" class="admin-btn admin-btn-secondary">
                        <i class="fas fa-chevron-left"></i> Previous
                    </a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="admin-btn <?php echo $i === $page ? 'admin-btn-primary' : 'admin-btn-secondary'; ?>">
                        <?php echo $i; ?>
                    </a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>" class="admin-btn admin-btn-secondary">
                        Next <i class="fas fa-chevron-right"></i>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html> 