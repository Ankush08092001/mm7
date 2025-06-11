<?php
require_once 'config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    
    $conn = getDBConnection();
    if ($conn) {
        $stmt = $conn->prepare("SELECT id, name, password_hash, role, is_pro_member FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password_hash'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['is_pro_member'] = $user['is_pro_member'];
                
                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: /admin/dashboard.php');
                } else {
                    header('Location: /user/dashboard.php');
                }
                exit();
            }
        }
        $error = 'Invalid email or password';
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MarineMonks</title>
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
                    <h1>Login to Your Account</h1>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-error">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="login.php" class="auth-form">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Login</button>
                    </form>
                    
                    <div class="auth-links">
                        <p>Don't have an account? <a href="signup.html">Sign up</a></p>
                        <p><a href="forgot-password.html">Forgot your password?</a></p>
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