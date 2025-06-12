<?php
session_start();
require_once __DIR__ . "/../config/db.php";

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

// Get user data
$user_id = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT name, email, membership_tier, profile_photo, last_login FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Get notifications count
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$notifications_count = $stmt->get_result()->fetch_assoc()['count'];

// Get recent test history
$stmt = $conn->prepare("
    SELECT mt.*, e.score, e.feedback 
    FROM mock_tests mt 
    LEFT JOIN evaluations e ON mt.id = e.test_id 
    WHERE mt.user_id = ? 
    ORDER BY mt.submitted_at DESC 
    LIMIT 5
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recent_tests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get recent bookmarks
$stmt = $conn->prepare("
    SELECT b.*, 
           CASE 
               WHEN b.resource_type = 'study_material' THEN sm.title
               WHEN b.resource_type = 'mock_test' THEN mt.title
               WHEN b.resource_type = 'paper' THEN p.title
           END as title
    FROM bookmarks b
    LEFT JOIN study_materials sm ON b.resource_type = 'study_material' AND b.resource_id = sm.id
    LEFT JOIN mock_tests mt ON b.resource_type = 'mock_test' AND b.resource_id = mt.id
    LEFT JOIN papers p ON b.resource_type = 'paper' AND b.resource_id = p.id
    WHERE b.user_id = ?
    ORDER BY b.created_at DESC
    LIMIT 5
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recent_bookmarks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MarineMonks</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/consolidated.css">
    <link rel="stylesheet" href="../css/animations.css">
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <style>
        /* Dashboard specific styles */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #1e3a5f;
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }

        .menu-item:hover, .menu-item.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .menu-item i {
            width: 20px;
            margin-right: 10px;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .notification-bell {
            position: relative;
            cursor: pointer;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #ef4444;
            color: white;
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 10px;
        }

        .notification-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            width: 300px;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            display: none;
            z-index: 1000;
        }

        .notification-item {
            padding: 12px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }

        .notification-item:hover {
            background-color: #f9fafb;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .dashboard-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e3a5f;
        }

        .welcome-card {
            grid-column: 1 / -1;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-size: 24px;
            font-weight: 600;
            color: #1e3a5f;
            margin-bottom: 5px;
        }

        .membership-badge {
            display: inline-block;
            padding: 4px 8px;
            background-color: #1e3a5f;
            color: white;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .test-history-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .test-info {
            flex: 1;
        }

        .test-title {
            font-weight: 500;
            color: #1e3a5f;
        }

        .test-date {
            font-size: 12px;
            color: #666;
        }

        .test-score {
            font-weight: 600;
            color: #1e3a5f;
        }

        .bookmark-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .bookmark-title {
            flex: 1;
            color: #1e3a5f;
        }

        .bookmark-actions {
            display: flex;
            gap: 10px;
        }

        .mobile-menu-toggle {
            display: none;
            font-size: 24px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../images/logo/logo-new.webp" alt="MarineMonks Logo">
                <h1>MarineMonks</h1>
            </div>
            
            <nav class="sidebar-menu">
                <a href="index.php" class="menu-item active">
                    <i class="fas fa-home"></i>
                    Home
                </a>
                <a href="study-materials.php" class="menu-item">
                    <i class="fas fa-book"></i>
                    Study Materials
                </a>
                <a href="papers.php" class="menu-item">
                    <i class="fas fa-file-alt"></i>
                    Previous Year Papers
                </a>
                <a href="mock-tests.php" class="menu-item">
                    <i class="fas fa-tasks"></i>
                    Mock Tests
                </a>
                <a href="submissions.php" class="menu-item">
                    <i class="fas fa-upload"></i>
                    My Submissions
                </a>
                <a href="bookmarks.php" class="menu-item">
                    <i class="fas fa-bookmark"></i>
                    Bookmarks
                </a>
                <a href="progress.php" class="menu-item">
                    <i class="fas fa-chart-line"></i>
                    Progress Tracker
                </a>
                <a href="membership.php" class="menu-item">
                    <i class="fas fa-crown"></i>
                    Membership
                </a>
                <a href="help.php" class="menu-item">
                    <i class="fas fa-question-circle"></i>
                    Help / Feedback
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <div class="mobile-menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
                
                <div class="notification-bell">
                    <i class="fas fa-bell"></i>
                    <?php if ($notifications_count > 0): ?>
                        <span class="notification-badge"><?php echo $notifications_count; ?></span>
                    <?php endif; ?>
                    <div class="notification-dropdown" id="notification-dropdown">
                        <!-- Notifications will be loaded here via AJAX -->
                    </div>
                </div>
            </div>

            <div class="dashboard-grid">
                <!-- Welcome Card -->
                <div class="dashboard-card welcome-card">
                    <img src="<?php echo $user['profile_photo'] ?? '../images/default-avatar.png'; ?>" 
                         alt="Profile Photo" 
                         style="width: 80px; height: 80px; border-radius: 50%;">
                    <div class="user-info">
                        <h2 class="user-name">Welcome, <?php echo htmlspecialchars($user['name']); ?></h2>
                        <span class="membership-badge"><?php echo ucfirst($user['membership_tier']); ?> Member</span>
                        <p>Last login: <?php echo date('M d, Y H:i', strtotime($user['last_login'])); ?></p>
                    </div>
                </div>

                <!-- Test History Card -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Tests</h3>
                        <a href="mock-tests.php" class="btn btn-outline">View All</a>
                    </div>
                    <?php foreach ($recent_tests as $test): ?>
                        <div class="test-history-item">
                            <div class="test-info">
                                <div class="test-title"><?php echo htmlspecialchars($test['title']); ?></div>
                                <div class="test-date"><?php echo date('M d, Y', strtotime($test['submitted_at'])); ?></div>
                            </div>
                            <div class="test-score">
                                <?php if ($test['score']): ?>
                                    <?php echo $test['score']; ?>%
                                <?php else: ?>
                                    Under Review
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Bookmarks Card -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Bookmarks</h3>
                        <a href="bookmarks.php" class="btn btn-outline">View All</a>
                    </div>
                    <?php foreach ($recent_bookmarks as $bookmark): ?>
                        <div class="bookmark-item">
                            <div class="bookmark-title"><?php echo htmlspecialchars($bookmark['title']); ?></div>
                            <div class="bookmark-actions">
                                <a href="<?php echo $bookmark['resource_type']; ?>.php?id=<?php echo $bookmark['resource_id']; ?>" 
                                   class="btn btn-sm">View</a>
                                <button class="btn btn-sm btn-danger" 
                                        onclick="removeBookmark(<?php echo $bookmark['id']; ?>)">Delete</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Notification dropdown
        document.querySelector('.notification-bell').addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = document.getElementById('notification-dropdown');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            
            if (dropdown.style.display === 'block') {
                loadNotifications();
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.notification-bell')) {
                document.getElementById('notification-dropdown').style.display = 'none';
            }
        });

        // Load notifications via AJAX
        function loadNotifications() {
            fetch('get_notifications.php')
                .then(response => response.json())
                .then(data => {
                    const dropdown = document.getElementById('notification-dropdown');
                    dropdown.innerHTML = data.notifications.map(notification => `
                        <div class="notification-item" onclick="markAsRead(${notification.id})">
                            <div class="notification-message">${notification.message}</div>
                            <div class="notification-time">${notification.created_at}</div>
                        </div>
                    `).join('');
                })
                .catch(error => console.error('Error loading notifications:', error));
        }

        // Mark notification as read
        function markAsRead(notificationId) {
            fetch('mark_notification_read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ notification_id: notificationId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
        }

        // Remove bookmark
        function removeBookmark(bookmarkId) {
            if (confirm('Are you sure you want to remove this bookmark?')) {
                fetch('remove_bookmark.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ bookmark_id: bookmarkId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error removing bookmark:', error));
            }
        }
    </script>
</body>
</html> 