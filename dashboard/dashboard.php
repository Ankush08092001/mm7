<?php
session_start();
require_once __DIR__ . '/../db.php';

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header('Location: /login.php');
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$user = getRow("SELECT * FROM users WHERE id = ?", [$user_id]);

if (!$user) {
    session_destroy();
    header('Location: /login.php');
    exit();
}

$username = htmlspecialchars($user['name']);
$useremail = htmlspecialchars($user['email']);
$is_pro_member = $user['is_pro_member'];

// Fetch recent 3 uploaded probables
$recent_probables = getRows("SELECT title, year, file_path, coming_soon FROM probables ORDER BY year DESC, id DESC LIMIT 3");

// Fetch study materials (example: top 3, or recent 3)
$study_materials = getRows("SELECT title, subject, file_path FROM study_materials LIMIT 3");

// Fetch user's submissions for 'My Submissions' section
$my_submissions = getRows("SELECT ans.file_path, ans.marks, ans.feedback, ans.status, mt.test_type, mt.question_path, ans.date_submitted FROM answersheets ans JOIN mock_tests mt ON ans.test_id = mt.id WHERE ans.user_id = ? ORDER BY ans.date_submitted DESC", [$user_id]);

// Fetch mock tests for 'Mock Test Access' section
$mock_tests = getRows("SELECT id, test_type, question_path FROM mock_tests");

// Placeholder for notifications (already implemented in previous phase)
$notifications = [
    ['message' => 'New study material on MEK-G available!', 'read' => false],
    ['message' => 'Your mock test results are ready!', 'read' => true],
    ['message' => 'Upcoming live session on Function 3.', 'read' => false],
];
$unread_notifications_count = count(array_filter($notifications, function($n) { return !$n['read']; }));

// Placeholder for progress summary (optional)
$tests_attempted = count($my_submissions);
$materials_downloaded = 0; // This would require tracking downloads in a real system

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - MarineMonks</title>
    <link rel="stylesheet" href="/css/consolidated.css">
    <link rel="stylesheet" href="/css/animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon">
    <style>
        /* Custom styles for dashboard page */
        .dashboard-main {
            padding: 20px 0;
        }
        .card {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 20px;
            backdrop-filter: blur(10px); /* Glassmorphism effect */
            background-color: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .welcome-block h1 {
            color: #0056b3;
            margin-top: 0;
        }
        .welcome-block .membership-status {
            font-weight: bold;
            color: #28a745;
        }
        .welcome-block .membership-status.free {
            color: #dc3545;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .quick-access-card {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            background-color: #f8f9fa;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }
        .quick-access-card:hover {
            transform: translateY(-5px);
        }
        .quick-access-card i {
            font-size: 2.5em;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        .quick-access-card h3 {
            margin-bottom: 10px;
            color: var(--dark-text);
        }
        .quick-access-card a {
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 500;
        }
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
        .progress-summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            text-align: center;
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
        .notifications-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 300px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            display: none; /* Hidden by default */
        }
        .notifications-dropdown.show {
            display: block;
        }
        .notifications-dropdown ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .notifications-dropdown li {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            font-size: 0.9em;
        }
        .notifications-dropdown li.unread {
            background-color: #e6f7ff;
            font-weight: bold;
        }
        .notifications-dropdown li:last-child {
            border-bottom: none;
        }
        .notifications-dropdown .no-notifications {
            padding: 15px;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <header role="banner">
        <div class="container">
            <nav class="navbar" role="navigation" aria-label="Main navigation">
                <a href="../index.php" class="logo" aria-label="MarineMonks Home">
                    <img src="../images/logo/logo-new.webp" alt="MarineMonks Logo" width="50" height="50" loading="lazy">
                    <span class="logo-text">MarineMonks</span>
                </a>
                
                <div class="mobile-menu-toggle" aria-expanded="false" aria-label="Toggle navigation menu" aria-controls="nav-links">
                    <i class="fas fa-bars"></i>
                </div>
                
                <ul class="nav-links" id="nav-links">
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../study-material.php">Study Material</a></li>
                    <li><a href="../mock-tests.php">Mock Tests</a></li>
                    <li><a href="../papers.html">Papers</a></li>
                    <li><a href="../probables.php">Probables</a></li>
                    <li><a href="dashboard.php" class="active">Dashboard</a></li>
                </ul>
                
                <div class="auth-buttons">
                    <div class="notification-bell" id="notificationBell">
                        <i class="fas fa-bell"></i>
                        <?php if ($unread_notifications_count > 0): ?>
                            <span class="badge"><?php echo $unread_notifications_count; ?></span>
                        <?php endif; ?>
                        <div class="notifications-dropdown" id="notificationsDropdown">
                            <?php if (!empty($notifications)): ?>
                                <ul>
                                    <?php foreach ($notifications as $notification): ?>
                                        <li class="<?php echo $notification['read'] ? '' : 'unread'; ?>">
                                            <?php echo htmlspecialchars($notification['message']); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="no-notifications">You're all caught up!</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <a href="logout.php" class="btn btn-outline">Logout</a>
                </div>
            </nav>
        </div>
    </header>

    <main class="dashboard-main">
        <div class="container">
            <section class="welcome-block card">
                <h1>👋 Welcome, <?php echo $username; ?>!</h1>
                <p>Email: <?php echo $useremail; ?></p>
                <p>Membership Status: <span class="membership-status <?php echo $is_pro_member ? 'pro' : 'free'; ?>"><?php echo $is_pro_member ? 'Pro Member' : 'Free Member'; ?></span></p>
                <?php if (!$is_pro_member): ?>
                    <a href="../pricing.html" class="btn btn-primary">Upgrade to Pro</a>
                <?php endif; ?>
            </section>

            <div class="dashboard-grid">
                <section class="study-material-access card">
                    <h2>📘 Study Material Access</h2>
                    <ul>
                        <?php foreach ($study_materials as $material): ?>
                            <li>
                                <span><?php echo htmlspecialchars($material['title']); ?> (<?php echo htmlspecialchars($material['subject']); ?>)</span>
                                <a href="<?php echo htmlspecialchars($material['file_path']); ?>" class="btn btn-sm" download>Download</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="../study-material.php" class="btn btn-outline">View All Study Materials</a>
                </section>

                <section class="probables-access card">
                    <h2>📑 Probables Access</h2>
                    <ul>
                        <?php foreach ($recent_probables as $probable): ?>
                            <li>
                                <span><?php echo htmlspecialchars($probable['title']); ?> (<?php echo htmlspecialchars($probable['year']); ?>)</span>
                                <?php if ($probable['coming_soon'] == 0): ?>
                                    <a href="<?php echo htmlspecialchars($probable['file_path']); ?>" class="btn btn-sm" download>Download</a>
                                <?php else: ?>
                                    <span class="coming-soon">Coming Soon</span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="../probables.php" class="btn btn-outline">View All Probables</a>
                </section>

                <section class="mock-test-access card">
                    <h2>🧪 Mock Test Access</h2>
                    <?php if ($is_pro_member): ?>
                        <?php foreach ($mock_tests as $test): ?>
                            <div class="mock-test-item">
                                <span><?php echo htmlspecialchars($test['test_type']); ?></span>
                                <a href="<?php echo htmlspecialchars($test['question_path']); ?>" class="btn btn-sm" download>Download Question</a>
                                <a href="upload_answersheet.php?test_id=<?php echo $test['id']; ?>" class="btn btn-sm">Upload Answer Sheet</a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="locked-content">
                            <p>Upgrade to Pro to access Mock Tests.</p>
                            <a href="../pricing.html" class="btn btn-primary">Upgrade to Pro</a>
                        </div>
                    <?php endif; ?>
                </section>

                <section class="my-submissions card">
                    <h2>🗂️ My Submissions</h2>
                    <?php if (!empty($my_submissions)): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Test Name</th>
                                    <th>Date Submitted</th>
                                    <th>Status</th>
                                    <th>Marks</th>
                                    <th>Feedback</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($my_submissions as $submission): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($submission['test_type']); ?></td>
                                        <td><?php echo htmlspecialchars($submission['date_submitted']); ?></td>
                                        <td><?php echo htmlspecialchars($submission['status']); ?></td>
                                        <td><?php echo htmlspecialchars($submission['marks'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($submission['feedback'] ?? 'N/A'); ?></td>
                                        <td>
                                            <a href="<?php echo htmlspecialchars($submission['file_path']); ?>" class="btn btn-sm" download>Download</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>You have not submitted any answer sheets yet.</p>
                    <?php endif; ?>
                </section>

                <section class="progress-summary card">
                    <h2>📊 Progress Summary</h2>
                    <div class="progress-summary-grid">
                        <div class="progress-item">
                            <h3>Tests Attempted</h3>
                            <p><?php echo $tests_attempted; ?></p>
                        </div>
                        <div class="progress-item">
                            <h3>Materials Downloaded</h3>
                            <p><?php echo $materials_downloaded; ?></p>
                        </div>
                    </div>
                </section>

                <section class="account-options card">
                    <h2>⚙️ Account Options</h2>
                    <a href="change-password.php" class="btn btn-primary">Change Password</a>
                    <a href="delete_account.php" class="btn btn-danger">Delete Account</a>
                </section>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> MarineMonks. All rights reserved.</p>
        </div>
    </footer>

    <script src="../js/navigation.js"></script>
    <script>
        // Notification dropdown logic
        document.getElementById('notificationBell').addEventListener('click', function() {
            document.getElementById('notificationsDropdown').classList.toggle('show');
        });

        // Close the dropdown if the user clicks outside of it
        window.addEventListener('click', function(event) {
            if (!event.target.matches('.notification-bell i') && !event.target.matches('.notification-bell .badge')) {
                var dropdowns = document.getElementsByClassName('notifications-dropdown');
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        });
    </script>
</body>
</html>

