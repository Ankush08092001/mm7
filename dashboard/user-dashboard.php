<?php
session_start();
require_once __DIR__ . '/db.php';

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header('Location: login.php');
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$user = getRow("SELECT * FROM users WHERE id = ?", [$user_id]);

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit();
}

$username = htmlspecialchars($user['name']);
$useremail = htmlspecialchars($user['email']);
$is_pro_member = $user['is_pro_member'];

// Fetch recent 3 uploaded probables
$recent_probables = getRows("SELECT title, year, file_path, coming_soon FROM probables ORDER BY year DESC, id DESC LIMIT 3");

// Fetch study materials
$study_materials = getRows("SELECT title, subject, file_path FROM study_materials LIMIT 3");

// Fetch user's submissions
$my_submissions = getRows("SELECT ans.file_path, ans.marks, ans.feedback, ans.status, mt.test_type, mt.question_path, ans.date_submitted FROM answersheets ans JOIN mock_tests mt ON ans.test_id = mt.id WHERE ans.user_id = ? ORDER BY ans.date_submitted DESC", [$user_id]);

// Fetch mock tests
$mock_tests = getRows("SELECT id, test_type, question_path FROM mock_tests");

// Notifications
$notifications = [
    ['message' => 'New study material on MEK-G available!', 'read' => false],
    ['message' => 'Your mock test results are ready!', 'read' => true],
    ['message' => 'Upcoming live session on Function 3.', 'read' => false],
];
$unread_notifications_count = count(array_filter($notifications, function($n) { return !$n['read']; }));

// Progress summary
$tests_attempted = count($my_submissions);
$materials_downloaded = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - MarineMonks</title>
    <meta name="description" content="Access your MarineMonks user dashboard. Track your MEO Class 4 exam progress, manage your subscription, access recent tests, and find recommended study content.">
    <link rel="stylesheet" href="css/consolidated.css">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="canonical" href="https://www.marinemonks.in/user-dashboard.html">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <style>
        /* User Dashboard Specific Styles */
        .dashboard-container {
            display: flex;
            min-height: calc(100vh - 70px);
        }
        
        /* Sidebar Styles */
        .dashboard-sidebar {
            width: 250px;
            background-color: #1a2e69;
            color: white;
            padding: 20px 0;
            transition: all 0.3s ease;
        }
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .logo {
            width: 150px;
            height: auto;
            margin-bottom: 15px;
        }
        .logo img {
            width: 100%;
            height: auto;
        }
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--secondary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.2rem;
            margin-right: 15px;
        }
        .user-info {
            flex: 1;
        }
        .user-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .user-plan {
            font-size: 0.8rem;
            opacity: 0.7;
            display: flex;
            align-items: center;
        }
        .user-plan-badge {
            display: inline-block;
            padding: 2px 8px;
            background-color: var(--secondary-color);
            border-radius: 20px;
            font-size: 0.7rem;
            margin-left: 5px;
        }
        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-nav-item {
            margin-bottom: 5px;
        }
        .sidebar-nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .sidebar-nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .sidebar-nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border-left: 4px solid var(--secondary-color);
        }
        .sidebar-icon {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content Styles */
        .dashboard-content {
            flex: 1;
            padding: 30px;
            background-color: #f5f7fa;
            overflow-y: auto;
        }
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .dashboard-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--dark-text);
        }
        .dashboard-actions {
            display: flex;
            gap: 15px;
        }
        .notification-bell {
            position: relative;
            cursor: pointer;
            margin-left: 20px;
        }
        .notification-bell .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.7em;
        }
        
        /* Welcome Section */
        .welcome-section {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 25px;
            margin-bottom: 30px;
        }
        .welcome-content {
            flex: 1;
        }
        .welcome-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 10px;
        }
        .welcome-subtitle {
            color: #666;
            margin-bottom: 15px;
        }
        .membership-status {
            font-weight: bold;
            color: #28a745;
        }
        .membership-status.free {
            color: #dc3545;
        }
        
        /* Progress Summary */
        .progress-summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        .progress-item {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .progress-item h3 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        .progress-item p {
            font-size: 1.5em;
            font-weight: bold;
        }
        
        /* Study Materials Section */
        .study-material-list ul,
        .probables-list ul {
            list-style: none;
            padding: 0;
        }
        .study-material-list li,
        .probables-list li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .study-material-list li:last-child,
        .probables-list li:last-child {
            border-bottom: none;
        }
        
        /* Mock Tests Section */
        .mock-test-item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .mock-test-item:last-child {
            border-bottom: none;
        }
        .mock-test-item .btn {
            margin-left: 10px;
        }
        
        /* My Submissions Section */
        .my-submissions table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .my-submissions th,
        .my-submissions td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .my-submissions th {
            background-color: #f2f2f2;
        }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            .dashboard-sidebar {
                width: 100%;
                position: relative;
            }
            .dashboard-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="dashboard-sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <a href="index.html">
                        <img src="images/logo/logo.png" alt="MarineMonks Logo">
                    </a>
                </div>
            </div>
            <div class="user-info">
                <div class="user-avatar"><?php echo substr($username, 0, 1); ?></div>
                <div class="user-name"><?php echo $username; ?></div>
                <div class="user-plan">
                    <?php echo $is_pro_member ? 'Pro Member' : 'Free Member'; ?>
                    <span class="user-plan-badge"><?php echo $is_pro_member ? 'PRO' : 'FREE'; ?></span>
                </div>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li class="sidebar-nav-item">
                        <a href="user-dashboard.html" class="sidebar-nav-link active">
                            <i class="fas fa-home sidebar-icon"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="user-courses.html" class="sidebar-nav-link">
                            <i class="fas fa-book sidebar-icon"></i>
                            My Courses
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="user-tests.html" class="sidebar-nav-link">
                            <i class="fas fa-file-alt sidebar-icon"></i>
                            My Tests
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="user-bookmarks.html" class="sidebar-nav-link">
                            <i class="fas fa-bookmark sidebar-icon"></i>
                            Bookmarks
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="user-certificates.html" class="sidebar-nav-link">
                            <i class="fas fa-certificate sidebar-icon"></i>
                            Certificates
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="user-settings.html" class="sidebar-nav-link">
                            <i class="fas fa-cog sidebar-icon"></i>
                            Settings
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-content">
            <div class="dashboard-header">
                <h1 class="dashboard-title">Dashboard</h1>
                <div class="dashboard-actions">
                    <div class="notification-bell">
                        <i class="fas fa-bell"></i>
                        <span class="badge"><?php echo $unread_notifications_count; ?></span>
                    </div>
                </div>
            </div>

            <!-- Welcome Section -->
            <section class="welcome-section">
                <div class="welcome-content">
                    <h2 class="welcome-title">Welcome back, <?php echo $username; ?>!</h2>
                    <p class="welcome-subtitle">Track your progress and access your learning materials</p>
                    <div class="membership-status <?php echo $is_pro_member ? '' : 'free'; ?>">
                        <?php echo $is_pro_member ? 'Pro Membership Active' : 'Free Membership'; ?>
                    </div>
                </div>
            </section>

            <!-- Progress Summary -->
            <section class="progress-summary-grid">
                <div class="progress-item">
                    <h3>Tests Attempted</h3>
                    <p><?php echo $tests_attempted; ?></p>
                </div>
                <div class="progress-item">
                    <h3>Materials Downloaded</h3>
                    <p><?php echo $materials_downloaded; ?></p>
                </div>
            </section>

            <!-- Recent Study Materials -->
            <section class="card">
                <h2>Recent Study Materials</h2>
                <div class="study-material-list">
                    <ul>
                        <?php foreach ($study_materials as $material): ?>
                        <li>
                            <span><?php echo htmlspecialchars($material['title']); ?> - <?php echo htmlspecialchars($material['subject']); ?></span>
                            <a href="<?php echo htmlspecialchars($material['file_path']); ?>" class="btn">Download</a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </section>

            <!-- Recent Probables -->
            <section class="card">
                <h2>Recent Probables</h2>
                <div class="probables-list">
                    <ul>
                        <?php foreach ($recent_probables as $probable): ?>
                        <li>
                            <span><?php echo htmlspecialchars($probable['title']); ?> (<?php echo htmlspecialchars($probable['year']); ?>)</span>
                            <?php if (!$probable['coming_soon']): ?>
                            <a href="<?php echo htmlspecialchars($probable['file_path']); ?>" class="btn">Download</a>
                            <?php else: ?>
                            <span class="coming-soon">Coming Soon</span>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </section>

            <!-- My Submissions -->
            <section class="card">
                <h2>My Recent Submissions</h2>
                <div class="my-submissions">
                    <table>
                        <thead>
                            <tr>
                                <th>Test Type</th>
                                <th>Date Submitted</th>
                                <th>Status</th>
                                <th>Marks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($my_submissions as $submission): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($submission['test_type']); ?></td>
                                <td><?php echo htmlspecialchars($submission['date_submitted']); ?></td>
                                <td><?php echo htmlspecialchars($submission['status']); ?></td>
                                <td><?php echo htmlspecialchars($submission['marks']); ?></td>
                                <td>
                                    <a href="<?php echo htmlspecialchars($submission['file_path']); ?>" class="btn">View</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Mock Tests -->
            <section class="card">
                <h2>Available Mock Tests</h2>
                <div class="mock-tests-list">
                    <?php foreach ($mock_tests as $test): ?>
                    <div class="mock-test-item">
                        <span><?php echo htmlspecialchars($test['test_type']); ?></span>
                        <a href="<?php echo htmlspecialchars($test['question_path']); ?>" class="btn">Start Test</a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>
    </div>

    <script src="js/dashboard.js"></script>
</body>
</html>
