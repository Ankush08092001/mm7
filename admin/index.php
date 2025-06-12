<?php
require_once 'auth_check.php';
requireAdminLogin();
updateSessionExpiry();

// Get admin info
$adminInfo = getAdminInfo();

// Fetch analytics data (dummy for now)
$total_users = 100;
$total_probables_views = 5000;
$total_study_materials_views = 8000;
$total_mock_tests_taken = 1200;

// Fetch recent mock test submissions (dummy for now)
$recent_submissions = [
    ["id" => 1, "username" => "User A", "test_type" => "Easy", "status" => "Pending", "submitted_at" => "2025-06-10 10:00:00"],
    ["id" => 2, "username" => "User B", "test_type" => "Medium", "status" => "Checked", "submitted_at" => "2025-06-09 15:30:00"],
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MarineMonks</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/consolidated.css">
    <link rel="stylesheet" href="../css/animations.css">
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
</head>
<body>
    <header>
        <nav>
            <div class="container">
                <a href="../index.php" class="logo">MarineMonks</a>
                <ul class="nav-links">
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../study-material.php">Study Material</a></li>
                    <li><a href="../mock-tests.php">Mock Tests</a></li>
                    <li><a href="../papers.html">Papers</a></li>
                    <li><a href="../probables.php">Probables</a></li>
                    <li><a href="../logout.php" class="btn btn-outline">Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="admin-dashboard">
        <div class="container">
            <h2>Admin Dashboard</h2>

            <section class="analytics-summary">
                <h3>Analytics Summary</h3>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h4>Total Users</h4>
                        <p><?php echo $total_users; ?></p>
                    </div>
                    <div class="stat-card">
                        <h4>Probables Views</h4>
                        <p><?php echo $total_probables_views; ?></p>
                    </div>
                    <div class="stat-card">
                        <h4>Study Materials Views</h4>
                        <p><?php echo $total_study_materials_views; ?></p>
                    </div>
                    <div class="stat-card">
                        <h4>Mock Tests Taken</h4>
                        <p><?php echo $total_mock_tests_taken; ?></p>
                    </div>
                </div>
            </section>

            <section class="content-management">
                <h3>Content Management</h3>
                <div class="admin-actions">
                    <a href="upload-probables.php" class="btn btn-primary">Upload Probables</a>
                    <a href="upload-study-material.php" class="btn btn-primary">Upload Study Material</a>
                    <a href="upload-mock-test.php" class="btn btn-primary">Upload Mock Test</a>
                </div>
            </section>

            <section class="mock-test-submissions">
                <h3>Recent Mock Test Submissions</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Test Type</th>
                            <th>Status</th>
                            <th>Submitted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_submissions as $submission): ?>
                            <tr>
                                <td><?php echo $submission["id"]; ?></td>
                                <td><?php echo $submission["username"]; ?></td>
                                <td><?php echo $submission["test_type"]; ?></td>
                                <td><?php echo $submission["status"]; ?></td>
                                <td><?php echo $submission["submitted_at"]; ?></td>
                                <td>
                                    <a href="review-submission.php?id=<?php echo $submission["id"]; ?>" class="btn btn-sm btn-secondary">Review</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <div class="user-actions">
                <div class="notification-bell">
                    <i class="fas fa-bell"></i>
                    <span class="badge">3</span>
                </div>
                <div class="user-profile">
                    <span class="admin-username"><?php echo htmlspecialchars($adminInfo['username']); ?></span>
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 MarineMonks. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

