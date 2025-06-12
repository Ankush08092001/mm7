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

// Placeholder for bookmarked study materials
// In a real application, this would be fetched from a 'bookmarks' table
$bookmarked_materials = [
    ['title' => 'MEO Class 4 Function 1 Notes', 'subject' => 'Function 1', 'author' => 'MarineMonks', 'file_path' => '#'],
    ['title' => 'Auxiliary Machinery Diagrams', 'subject' => 'Function 3', 'author' => 'MarineMonks', 'file_path' => '#'],
];

// Simulate removal for demonstration (in a real app, this would update the database)
if (isset($_GET['remove_bookmark'])) {
    $remove_id = $_GET['remove_bookmark'];
    // In a real application, execute a DELETE query here
    // For now, we'll just simulate by redirecting
    header('Location: bookmarks.php');
    exit();
}

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
    <title>My Bookmarks - MarineMonks</title>
    <link rel="stylesheet" href="../css/consolidated.css">
    <link rel="stylesheet" href="../css/animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <style>
        /* Custom styles for bookmarks page */
        .bookmarks-main {
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
        .material-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .material-item {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .material-item h4 {
            color: #0056b3;
            margin-top: 0;
        }
        .material-item p {
            margin-bottom: 5px;
            font-size: 0.9em;
        }
        .material-item .btn {
            margin-right: 10px;
            margin-top: 10px;
        }
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #777;
        }
        .empty-state i {
            font-size: 4em;
            color: #ccc;
            margin-bottom: 20px;
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
                    <li><a href="bookmarks.php" class="active">Bookmarks</a></li>
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

    <main class="bookmarks-main">
        <div class="container">
            <h1>Your Bookmarked Study Materials 📚</h1>

            <section class="bookmarks-list card">
                <?php if (!empty($bookmarked_materials)): ?>
                    <div class="material-grid">
                        <?php foreach ($bookmarked_materials as $index => $material): ?>
                            <div class="material-item">
                                <h4><?php echo htmlspecialchars($material['title']); ?></h4>
                                <p>Subject: <?php echo htmlspecialchars($material['subject']); ?></p>
                                <p>Author: <?php echo htmlspecialchars($material['author']); ?></p>
                                <a href="<?php echo htmlspecialchars($material['file_path']); ?>" class="btn btn-sm" download>View PDF</a>
                                <a href="bookmarks.php?remove_bookmark=<?php echo $index; ?>" class="btn btn-sm btn-danger">Remove</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-bookmark"></i>
                        <p>You haven’t bookmarked anything yet!</p>
                    </div>
                <?php endif; ?>
            </section>

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

