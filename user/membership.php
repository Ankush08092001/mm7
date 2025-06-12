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

// Placeholder for membership dates (in a real app, these would come from the database)
$membership_start = '2023-01-01';
$membership_expiry = $is_pro_member ? '2024-01-01' : 'N/A';

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
    <title>Membership Details - MarineMonks</title>
    <link rel="stylesheet" href="../css/consolidated.css">
    <link rel="stylesheet" href="../css/animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <style>
        /* Custom styles for membership page */
        .membership-main {
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
        .membership-status h2 {
            color: #0056b3;
            margin-bottom: 10px;
        }
        .membership-status p {
            margin-bottom: 5px;
        }
        .feature-comparison table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .feature-comparison th, .feature-comparison td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .feature-comparison th {
            background-color: #f2f2f2;
        }
        .feature-comparison .check-icon {
            color: green;
            font-weight: bold;
        }
        .feature-comparison .cross-icon {
            color: red;
            font-weight: bold;
        }
        .upgrade-section {
            text-align: center;
            margin-top: 30px;
        }
        .pro-badge {
            background-color: gold;
            color: #333;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
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
                    <li><a href="membership.php" class="active">Membership</a></li>
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

    <main class="membership-main">
        <div class="container">
            <h1>Membership Details</h1>

            <section class="membership-status card">
                <h2>Current Plan: <strong><?php echo $is_pro_member ? 'Pro Member' : 'Free Member'; ?></strong></h2>
                <?php if ($is_pro_member): ?>
                    <span class="pro-badge">PRO</span>
                <?php endif; ?>
                <p>Membership Start: <?php echo htmlspecialchars($membership_start); ?></p>
                <p>Expiry Date: <?php echo htmlspecialchars($membership_expiry); ?></p>
            </section>

            <section class="feature-comparison card">
                <h2>Feature Comparison</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Feature</th>
                            <th>Free</th>
                            <th>Pro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Study Materials</td>
                            <td><span class="check-icon">✅</span></td>
                            <td><span class="check-icon">✅</span></td>
                        </tr>
                        <tr>
                            <td>Probables</td>
                            <td><span class="check-icon">✅</span></td>
                            <td><span class="check-icon">✅</span></td>
                        </tr>
                        <tr>
                            <td>Mock Tests (Written & Oral)</td>
                            <td><span class="cross-icon">❌</span></td>
                            <td><span class="check-icon">✅</span></td>
                        </tr>
                        <tr>
                            <td>Feedback by Surveyors</td>
                            <td><span class="cross-icon">❌</span></td>
                            <td><span class="check-icon">✅</span></td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <?php if (!$is_pro_member): ?>
            <section class="upgrade-section card">
                <h2>Unlock More Features!</h2>
                <p>Upgrade to Pro to get access to exclusive mock tests, personalized feedback, and more.</p>
                <a href="../pricing.html" class="btn btn-primary">Upgrade to Pro</a>
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

