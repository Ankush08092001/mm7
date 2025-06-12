<?php
session_start();
require_once __DIR__ . 
'/../db.php';

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header('Location: ../login.php');
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$user = getRow("SELECT * FROM users WHERE id = ?", [$user_id]);

if (!$user) {
    session_destroy();
    header('Location: ../login.php');
    exit();
}

$is_pro_member = $user['is_pro_member'];

// Placeholder for user's attempted tests (for Pro members)
$attempted_tests = [
    ['test_name' => 'Mock Test 1', 'date' => '2023-01-15', 'status' => 'Reviewed', 'answer_link' => '#', 'feedback_link' => '#'],
    ['test_name' => 'Mock Test 2', 'date' => '2023-02-01', 'status' => 'Pending', 'answer_link' => '#', 'feedback_link' => ''],
    ['test_name' => 'Mock Test 3', 'date' => '2023-03-10', 'status' => 'Reviewed', 'answer_link' => '#', 'feedback_link' => '#'],
];

// Placeholder for notifications
$notifications = [
    ['message' => 'New study material on MEK-G available!', 'read' => false],
    ['message' => 'Your mock test results are ready!', 'read' => true],
    ['message' => 'Upcoming live session on Function 3.', 'read' => false],
];
$unread_notifications_count = count(array_filter($notifications, function($n) { return !$n['read']; }));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tests - MarineMonks</title>
    <link rel="stylesheet" href="../css/consolidated.css">
    <link rel="stylesheet" href="../css/animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <style>
        /* Custom styles for my-tests page */
        .my-tests-main {
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
        .test-card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        .test-card {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .test-card h3 {
            color: #0056b3;
            margin-top: 0;
        }
        .test-card p {
            margin-bottom: 5px;
        }
        .test-card .btn {
            margin-right: 10px;
            margin-top: 10px;
        }
        .locked-overlay {
            position: relative;
            filter: blur(4px);
            pointer-events: none;
            opacity: 0.6;
        }
        .locked-message {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            z-index: 10;
            width: 80%;
            max-width: 400px;
        }
        .locked-message h2 {
            color: #fff;
            margin-top: 0;
        }
        .locked-message .btn {
            margin-top: 20px;
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
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="my-tests.php" class="active">My Tests</a></li>
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

    <main class="my-tests-main">
        <div class="container">
            <h1>My Tests</h1>

            <?php if ($is_pro_member): ?>
                <section class="pro-content card">
                    <h2>Your Attempted Tests</h2>
                    <div class="test-card-grid">
                        <?php if (!empty($attempted_tests)): ?>
                            <?php foreach ($attempted_tests as $test): ?>
                                <div class="test-card">
                                    <h3><?php echo htmlspecialchars($test['test_name']); ?></h3>
                                    <p>Date: <?php echo htmlspecialchars($test['date']); ?></p>
                                    <p>Status: <strong><?php echo htmlspecialchars($test['status']); ?></strong></p>
                                    <?php if ($test['status'] == 'Reviewed'): ?>
                                        <a href="<?php echo htmlspecialchars($test['answer_link']); ?>" class="btn btn-sm">View Answer</a>
                                        <a href="<?php echo htmlspecialchars($test['feedback_link']); ?>" class="btn btn-sm">View Feedback</a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>You haven't attempted any tests yet.</p>
                        <?php endif; ?>
                    </div>
                </section>
            <?php else: ?>
                <section class="free-content card">
                    <div class="locked-overlay">
                        <h2>Your Attempted Tests</h2>
                        <div class="test-card-grid">
                            <!-- Blurred placeholder content -->
                            <div class="test-card"><h3>Mock Test 1</h3><p>Date: XXXX-XX-XX</p><p>Status: Blurred</p></div>
                            <div class="test-card"><h3>Mock Test 2</h3><p>Date: XXXX-XX-XX</p><p>Status: Blurred</p></div>
                        </div>
                    </div>
                    <div class="locked-message">
                        <h2>🔒 Premium Members Only</h2>
                        <p>Access mock tests, surveyor feedback, and real-time evaluation.</p>
                        <a href="../pricing.html" class="btn btn-primary">Upgrade to Pro</a>
                    </div>
                </section>
            <?php endif; ?>

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

