<?php
require_once 'config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
    // Validate input
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'All fields are required';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long';
    } else {
        $conn = getDBConnection();
        if ($conn) {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error = 'Email already registered';
            } else {
                // Create new user
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $email, $passwordHash);
                
                if ($stmt->execute()) {
                    $success = 'Registration successful! You can now login.';
                } else {
                    $error = 'Registration failed. Please try again.';
                }
            }
            $stmt->close();
            $conn->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - MarineMonks</title>
    <link rel="stylesheet" href="css/consolidated.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
</head>
<body>
    <!-- Skip to content link for accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>
    
    <!-- Header -->
    <header>
        <div class="container">
            <nav class="navbar">
                <a href="index.html" class="logo" aria-label="MarineMonks Home">
                    <img src="images/logo/logo-new.webp" alt="MarineMonks Logo" width="50" height="50">
                    <span class="logo-text">MarineMonks</span>
                </a>
                
                <div class="mobile-menu-toggle" aria-expanded="false" aria-label="Toggle navigation menu">
                    <i class="fas fa-bars"></i>
                </div>
                
                <ul class="nav-links">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="study-material.html">Study Material</a></li>
                    <li><a href="mock-tests.html">Mock Tests</a></li>
                    <li><a href="papers.html">Papers</a></li>
                    <li><a href="probables.html">Probables</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="auth-section" id="main-content">
            <div class="container">
                <div class="auth-card">
                    <h1>Create Your Account</h1>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-error">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="signup.php" class="auth-form">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                            <small>Must be at least 8 characters long</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Sign Up</button>
                    </form>
                    
                    <div class="auth-links">
                        <p>Already have an account? <a href="login.php">Login</a></p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <a href="index.html" class="footer-logo">
                        <img src="images/logo/logo-new.webp" alt="MarineMonks Logo" width="40" height="40">
                        <span>MarineMonks</span>
                    </a>
                    <p>India's premier marine engineering educational platform for MEO Class 4 exam preparation.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li><a href="study-material.html">Study Material</a></li>
                        <li><a href="mock-tests.html">Mock Tests</a></li>
                        <li><a href="papers.html">Papers</a></li>
                        <li><a href="probables.html">Probables</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="contact.html">Contact Us</a></li>
                        <li><a href="faq.html">FAQ</a></li>
                        <li><a href="help.html">Help Center</a></li>
                        <li><a href="feedback.html">Feedback</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="privacy.html">Privacy Policy</a></li>
                        <li><a href="terms.html">Terms of Service</a></li>
                        <li><a href="refund.html">Refund Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 MarineMonks. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="js/navigation.js"></script>
</body>
</html> 