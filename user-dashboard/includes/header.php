<?php
require_once 'auth.php';
require_once 'db.php';

// Ensure user is logged in
requireLogin();

// Update last login time
updateLastLogin();

// Get user data
$user_data = getUserData();
$notifications_count = getNotificationsCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarineMonks - User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/consolidated.css">
    <link rel="stylesheet" href="../css/animations.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <style>
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 1rem;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1rem;
        }
        
        .sidebar-header h2 {
            margin: 0;
            font-size: 1.5rem;
        }
        
        .nav-item {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .nav-item:hover, .nav-item.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .nav-item i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
            background: #f8f9fa;
        }
        
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .notification-bell {
            position: relative;
            cursor: pointer;
            padding: 0.5rem;
        }
        
        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        #notification-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            width: 300px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: none;
            z-index: 1000;
        }
        
        .notification-item {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }
        
        .notification-item:hover {
            background: #f8f9fa;
        }
        
        .notification-message {
            margin-bottom: 0.25rem;
        }
        
        .notification-time {
            font-size: 0.8rem;
            color: #666;
        }
        
        .mobile-menu-toggle {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
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
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../images/logo/logo-new.webp" alt="MarineMonks Logo">
                <h2>MarineMonks</h2>
            </div>
            
            <nav>
                <a href="index.php" class="nav-item <?php echo $current_page === 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="study-materials.php" class="nav-item <?php echo $current_page === 'study-materials' ? 'active' : ''; ?>">
                    <i class="fas fa-book"></i> Study Materials
                </a>
                <a href="papers.php" class="nav-item <?php echo $current_page === 'papers' ? 'active' : ''; ?>">
                    <i class="fas fa-file-alt"></i> Previous Year Papers
                </a>
                <a href="mock-tests.php" class="nav-item <?php echo $current_page === 'mock-tests' ? 'active' : ''; ?>">
                    <i class="fas fa-tasks"></i> Mock Tests
                </a>
                <a href="submissions.php" class="nav-item <?php echo $current_page === 'submissions' ? 'active' : ''; ?>">
                    <i class="fas fa-upload"></i> My Submissions
                </a>
                <a href="bookmarks.php" class="nav-item <?php echo $current_page === 'bookmarks' ? 'active' : ''; ?>">
                    <i class="fas fa-bookmark"></i> Bookmarks
                </a>
                <a href="progress.php" class="nav-item <?php echo $current_page === 'progress' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i> Progress Tracker
                </a>
                <a href="membership.php" class="nav-item <?php echo $current_page === 'membership' ? 'active' : ''; ?>">
                    <i class="fas fa-crown"></i> Membership
                </a>
                <a href="help.php" class="nav-item <?php echo $current_page === 'help' ? 'active' : ''; ?>">
                    <i class="fas fa-question-circle"></i> Help / Feedback
                </a>
            </nav>
        </aside>
        
        <main class="main-content">
            <div class="top-bar">
                <div class="mobile-menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
                
                <div class="user-profile">
                    <img src="<?php echo $user_data['profile_photo'] ?: '../images/default-avatar.png'; ?>" 
                         alt="Profile" class="profile-image">
                    <div class="user-info">
                        <h3><?php echo htmlspecialchars($user_data['name']); ?></h3>
                        <p><?php echo ucfirst($user_data['membership_tier']); ?> Member</p>
                    </div>
                </div>
                
                <div class="notification-bell">
                    <i class="fas fa-bell"></i>
                    <?php if ($notifications_count > 0): ?>
                        <span class="notification-badge"><?php echo $notifications_count; ?></span>
                    <?php endif; ?>
                    <div id="notification-dropdown"></div>
                </div>
            </div>
        </main>
    </div>
</body>
</html> 