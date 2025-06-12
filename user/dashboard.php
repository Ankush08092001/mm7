<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

// Get user data
$user = getRow("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
if (!$user) {
    session_destroy();
    header('Location: /login.php');
    exit();
}

// Get user's recent study materials
$recentMaterials = getRows(
    "SELECT * FROM study_materials 
    WHERE id IN (SELECT material_id FROM user_materials WHERE user_id = ?)
    ORDER BY created_at DESC LIMIT 5",
    [$user['id']]
);

// Get recent probables
$recentProbables = getRows(
    "SELECT * FROM probables 
    WHERE coming_soon = 0 
    ORDER BY created_at DESC LIMIT 3"
);

// Get user's mock tests
$mockTests = getRows(
    "SELECT mt.*, 
    CASE 
        WHEN a.id IS NULL THEN 'Not Attempted'
        WHEN a.status = 'pending' THEN 'Submitted'
        WHEN a.status = 'checked' THEN 'Evaluated'
    END as test_status,
    a.marks, a.feedback
    FROM mock_tests mt
    LEFT JOIN answersheets a ON mt.id = a.test_id AND a.user_id = ?
    WHERE mt.is_pro_only = 0 OR ? = 1
    ORDER BY mt.created_at DESC",
    [$user['id'], $user['is_pro_member']]
);

// Get user's submissions
$submissions = getRows(
    "SELECT a.*, mt.title as test_title 
    FROM answersheets a
    JOIN mock_tests mt ON a.test_id = mt.id
    WHERE a.user_id = ?
    ORDER BY a.created_at DESC",
    [$user['id']]
);

// Get user's progress stats
$stats = getRow(
    "SELECT 
    (SELECT COUNT(*) FROM answersheets WHERE user_id = ?) as tests_attempted,
    (SELECT COUNT(*) FROM user_materials WHERE user_id = ?) as materials_downloaded",
    [$user['id'], $user['id']]
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Exam Prep</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .welcome-block {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card h2 {
            margin-top: 0;
            color: #333;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn-pro {
            background: #28a745;
        }
        .btn-pro:hover {
            background: #218838;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
        }
        .status-pending { background: #ffc107; }
        .status-checked { background: #28a745; }
        .status-not-attempted { background: #6c757d; }
        @media (max-width: 768px) {
            .grid-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Welcome Block -->
        <div class="welcome-block">
            <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
            <p>Membership: <?php echo $user['is_pro_member'] ? 'Pro Member' : 'Free Member'; ?></p>
            <?php if (!$user['is_pro_member']): ?>
                <a href="/pricing.php" class="btn btn-pro">Upgrade to Pro</a>
            <?php endif; ?>
        </div>

        <!-- Study Materials -->
        <div class="card">
            <h2>Study Materials</h2>
            <div class="grid-container">
                <a href="/study-material.php?type=written" class="btn">Written Materials</a>
                <a href="/study-material.php?type=oral" class="btn">Oral Materials</a>
            </div>
            <?php if ($recentMaterials): ?>
                <h3>Recent Materials</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Subject</th>
                                <th>Topic</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentMaterials as $material): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($material['title']); ?></td>
                                    <td><?php echo htmlspecialchars($material['subject']); ?></td>
                                    <td><?php echo htmlspecialchars($material['topic']); ?></td>
                                    <td>
                                        <a href="/backend/study_materials.php/download/<?php echo $material['file_path']; ?>" class="btn">Download</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Probables -->
        <div class="card">
            <h2>Probables</h2>
            <a href="/probables.php" class="btn">View All Probables</a>
            <?php if ($recentProbables): ?>
                <h3>Recent Probables</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Year</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentProbables as $probable): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($probable['title']); ?></td>
                                    <td><?php echo htmlspecialchars($probable['year']); ?></td>
                                    <td>
                                        <a href="/backend/study_materials.php/download/<?php echo $probable['file_path']; ?>" class="btn">Download</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($user['is_pro_member']): ?>
        <!-- Mock Tests -->
        <div class="card">
            <h2>Mock Tests</h2>
            <div class="grid-container">
                <?php foreach ($mockTests as $test): ?>
                    <div class="test-card">
                        <h3><?php echo htmlspecialchars($test['title']); ?></h3>
                        <p>Type: <?php echo htmlspecialchars($test['test_type']); ?></p>
                        <p>Status: <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $test['test_status'])); ?>">
                            <?php echo $test['test_status']; ?>
                        </span></p>
                        <?php if ($test['test_status'] === 'Not Attempted'): ?>
                            <a href="/mock-test.php?id=<?php echo $test['id']; ?>" class="btn">Start Test</a>
                        <?php elseif ($test['test_status'] === 'Submitted'): ?>
                            <p>Your answer sheet is being evaluated</p>
                        <?php else: ?>
                            <p>Marks: <?php echo $test['marks']; ?></p>
                            <p>Feedback: <?php echo htmlspecialchars($test['feedback']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- My Submissions -->
        <div class="card">
            <h2>My Submissions</h2>
            <?php if ($submissions): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Test Name</th>
                                <th>Date Submitted</th>
                                <th>Status</th>
                                <th>Marks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($submissions as $submission): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($submission['test_title']); ?></td>
                                    <td><?php echo date('Y-m-d H:i', strtotime($submission['created_at'])); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $submission['status']; ?>">
                                            <?php echo ucfirst($submission['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $submission['marks'] ?? 'N/A'; ?></td>
                                    <td>
                                        <a href="/backend/study_materials.php/download/<?php echo $submission['file_path']; ?>" class="btn">Download</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No submissions yet.</p>
            <?php endif; ?>
        </div>

        <!-- Progress Summary -->
        <div class="card">
            <h2>Progress Summary</h2>
            <div class="grid-container">
                <div class="stat-card">
                    <h3>Tests Attempted</h3>
                    <p><?php echo $stats['tests_attempted']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Materials Downloaded</h3>
                    <p><?php echo $stats['materials_downloaded']; ?></p>
                </div>
            </div>
        </div>

        <!-- Account Options -->
        <div class="card">
            <h2>Account Options</h2>
            <form action="/user/change-password.php" method="POST" class="form">
                <h3>Change Password</h3>
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn">Change Password</button>
            </form>
            <a href="/logout.php" class="btn" style="margin-top: 20px;">Logout</a>
        </div>
    </div>

    <script>
        // Password validation
        document.querySelector('.form').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('New passwords do not match!');
            }
        });
    </script>
</body>
</html> 