<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

// Get user's test history
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM user_tests WHERE user_id = ? ORDER BY test_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$tests = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tests - User Dashboard - MarineMonks</title>
    <meta name="description" content="Access your test history and upcoming tests on MarineMonks.">
    <link rel="stylesheet" href="/css/consolidated.css">
    <link rel="stylesheet" href="/css/animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <aside class="dashboard-sidebar">
            <div class="sidebar-header">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-info">
                    <div class="user-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></div>
                    <div class="user-plan">
                        <?php echo htmlspecialchars($_SESSION['user_plan'] ?? 'Free Plan'); ?>
                        <span class="user-plan-badge">Active</span>
                    </div>
                </div>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li class="sidebar-nav-item">
                        <a href="/dashboard/dashboard.php" class="sidebar-nav-link">
                            <i class="fas fa-home sidebar-icon"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="/dashboard/user-tests.php" class="sidebar-nav-link active">
                            <i class="fas fa-file-alt sidebar-icon"></i>
                            My Tests
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="/dashboard/user-courses.php" class="sidebar-nav-link">
                            <i class="fas fa-book sidebar-icon"></i>
                            My Courses
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="/dashboard/user-certificates.php" class="sidebar-nav-link">
                            <i class="fas fa-certificate sidebar-icon"></i>
                            Certificates
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="/dashboard/user-bookmarks.php" class="sidebar-nav-link">
                            <i class="fas fa-bookmark sidebar-icon"></i>
                            Bookmarks
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="/dashboard/user-subscription.php" class="sidebar-nav-link">
                            <i class="fas fa-crown sidebar-icon"></i>
                            Subscription
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="/dashboard/user-support.php" class="sidebar-nav-link">
                            <i class="fas fa-headset sidebar-icon"></i>
                            Support
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="/dashboard/user-settings.php" class="sidebar-nav-link">
                            <i class="fas fa-cog sidebar-icon"></i>
                            Settings
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="/dashboard/user-profile.php" class="sidebar-nav-link">
                            <i class="fas fa-user sidebar-icon"></i>
                            Profile
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="/logout.php" class="sidebar-nav-link">
                            <i class="fas fa-sign-out-alt sidebar-icon"></i>
                            Logout
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        <!-- Main Content -->
        <main class="dashboard-content">
            <div class="dashboard-header">
                <h1 class="dashboard-title">My Tests</h1>
                <div class="dashboard-actions">
                    <a href="/mock-tests.php" class="dashboard-btn primary">
                        <i class="fas fa-plus"></i> Take New Test
                    </a>
                </div>
            </div>
            <!-- Rest of the content remains unchanged -->
        </main>
    </div>
</body>
</html> 