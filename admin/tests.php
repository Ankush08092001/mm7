<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Include database configuration
require_once '../config/database.php';

// Handle test actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'delete':
                if (isset($_POST['test_id'])) {
                    // First delete related records
                    $stmt = $pdo->prepare("DELETE FROM test_questions WHERE test_id = ?");
                    $stmt->execute([$_POST['test_id']]);
                    
                    $stmt = $pdo->prepare("DELETE FROM test_results WHERE test_id = ?");
                    $stmt->execute([$_POST['test_id']]);
                    
                    // Then delete the test
                    $stmt = $pdo->prepare("DELETE FROM mock_tests WHERE id = ?");
                    $stmt->execute([$_POST['test_id']]);
                }
                break;
            
            case 'toggle_status':
                if (isset($_POST['test_id'])) {
                    $stmt = $pdo->prepare("UPDATE mock_tests SET is_active = NOT is_active WHERE id = ?");
                    $stmt->execute([$_POST['test_id']]);
                }
                break;
        }
    }
}

// Get tests with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Get total tests count
$total_tests = $pdo->query("SELECT COUNT(*) FROM mock_tests")->fetchColumn();
$total_pages = ceil($total_tests / $per_page);

// Get tests for current page
$stmt = $pdo->prepare("
    SELECT mt.*, 
           COUNT(DISTINCT tq.id) as total_questions,
           COUNT(DISTINCT tr.id) as total_attempts,
           AVG(tr.score) as average_score
    FROM mock_tests mt
    LEFT JOIN test_questions tq ON mt.id = tq.test_id
    LEFT JOIN test_results tr ON mt.id = tr.test_id
    GROUP BY mt.id
    ORDER BY mt.created_at DESC
    LIMIT ? OFFSET ?
");
$stmt->execute([$per_page, $offset]);
$tests = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mock Tests Management - Admin Portal</title>
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
                <h1 class="admin-header-title">Mock Tests Management</h1>
                <div class="admin-header-actions">
                    <a href="add_test.php" class="admin-btn admin-btn-primary">
                        <i class="fas fa-plus"></i> Add Test
                    </a>
                </div>
            </header>

            <!-- Tests Table -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">All Mock Tests</h2>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Questions</th>
                            <th>Attempts</th>
                            <th>Avg. Score</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tests as $test): ?>
                        <tr>
                            <td><?php echo $test['id']; ?></td>
                            <td><?php echo htmlspecialchars($test['title']); ?></td>
                            <td><?php echo $test['total_questions']; ?></td>
                            <td><?php echo $test['total_attempts']; ?></td>
                            <td><?php echo $test['average_score'] ? number_format($test['average_score'], 1) . '%' : 'N/A'; ?></td>
                            <td><?php echo $test['duration']; ?> minutes</td>
                            <td>
                                <span class="status-badge <?php echo $test['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                    <?php echo $test['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($test['created_at'])); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="toggle_status">
                                    <input type="hidden" name="test_id" value="<?php echo $test['id']; ?>">
                                    <button type="submit" class="admin-btn admin-btn-secondary">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                </form>
                                <a href="edit_test.php?id=<?php echo $test['id']; ?>" class="admin-btn admin-btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="test_questions.php?id=<?php echo $test['id']; ?>" class="admin-btn admin-btn-secondary">
                                    <i class="fas fa-list"></i>
                                </a>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this test? This will also delete all related questions and results.');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="test_id" value="<?php echo $test['id']; ?>">
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