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

$feedback_message = '';
$feedback_type = '';
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback_type = $_POST['feedback_type'] ?? '';
    $feedback_message = $_POST['feedback_message'] ?? '';

    if (empty($feedback_type) || empty($feedback_message)) {
        $error_message = 'Please fill in all required fields.';
    } else {
        // In a real application, you would save this feedback to a database
        // For now, we'll just simulate success
        $success_message = 'Thank you for your feedback!';
        // Clear form fields after successful submission
        $feedback_message = '';
        $feedback_type = '';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - MarineMonks</title>
    <link rel="stylesheet" href="/css/consolidated.css">
    <link rel="stylesheet" href="/css/animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon">
    <style>
        /* Custom styles for feedback page */
        .feedback-main {
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
        .feedback-form .form-group {
            margin-bottom: 15px;
        }
        .feedback-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .feedback-form input[type="text"],
        .feedback-form input[type="email"],
        .feedback-form select,
        .feedback-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .feedback-form textarea {
            resize: vertical;
            min-height: 100px;
        }
        .feedback-form .btn-primary {
            width: auto;
            padding: 10px 20px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
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
                    <li><a href="feedback.php" class="active">Feedback</a></li>
                </ul>
                
                <div class="auth-buttons">
                    <a href="logout.php" class="btn btn-outline">Logout</a>
                </div>
            </nav>
        </div>
    </header>

    <main class="feedback-main">
        <div class="container">
            <h1>Contact / Feedback</h1>

            <section class="feedback-form-section card">
                <?php if ($success_message): ?>
                    <div class="message success">✅ <?php echo $success_message; ?></div>
                <?php endif; ?>
                <?php if ($error_message): ?>
                    <div class="message error">❌ <?php echo $error_message; ?></div>
                <?php endif; ?>

                <form action="feedback.php" method="POST" class="feedback-form">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo $username; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo $useremail; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="feedback_type">Feedback Type:</label>
                        <select id="feedback_type" name="feedback_type" required>
                            <option value="">Select a type</option>
                            <option value="Bug" <?php echo ($feedback_type == 'Bug') ? 'selected' : ''; ?>>Bug</option>
                            <option value="Suggestion" <?php echo ($feedback_type == 'Suggestion') ? 'selected' : ''; ?>>Suggestion</option>
                            <option value="Praise" <?php echo ($feedback_type == 'Praise') ? 'selected' : ''; ?>>Praise</option>
                            <option value="Complaint" <?php echo ($feedback_type == 'Complaint') ? 'selected' : ''; ?>>Complaint</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="feedback_message">Feedback Message:</label>
                        <textarea id="feedback_message" name="feedback_message" required><?php echo $feedback_message; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                </form>
            </section>

        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> MarineMonks. All rights reserved.</p>
        </div>
    </footer>

    <script src="../js/navigation.js"></script>
</body>
</html>

