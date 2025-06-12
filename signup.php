<?php
session_start();
require_once __DIR__ . "/config/db.php";

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            $success_message = "Account created successfully! You can now <a href=\"login.php\">login</a>.";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - MarineMonks</title>
    <link rel="stylesheet" href="css/consolidated.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <div class="container">
                <a href="index.html" class="logo">MarineMonks</a>
                <ul class="nav-links">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="study-material.html">Study Material</a></li>
                    <li><a href="mock-tests.html">Mock Tests</a></li>
                    <li><a href="papers.html">Papers</a></li>
                    <li><a href="probables.html">Probables</a></li>
                    <li><a href="login.php" class="btn btn-primary">Login</a></li>
                    <li><a href="signup.php" class="btn btn-secondary">Sign Up</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="signup-page">
        <div class="container">
            <div class="signup-form">
                <h2>Create your account</h2>
                <?php if (!empty($error_message)): ?>
                    <p class="error-message"><?php echo $error_message; ?></p>
                <?php endif; ?>
                <?php if (!empty($success_message)): ?>
                    <p class="success-message"><?php echo $success_message; ?></p>
                <?php endif; ?>
                <form action="signup.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Your username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" id="email" name="email" placeholder="you@example.com" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm password</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" required>
                    </div>
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">I agree to the <a href="terms-of-service.html">Terms of Service</a> and <a href="privacy-policy.html">Privacy Policy</a></label>
                    </div>
                    <button type="submit" class="btn btn-primary">Create account</button>
                </form>
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="footer-links">
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="study-material.html">Study Material</a></li>
                    <li><a href="mock-tests.html">Mock Tests</a></li>
                    <li><a href="papers.html">Papers</a></li>
                </ul>
                <ul>
                    <li><a href="privacy-policy.html">Privacy Policy</a></li>
                    <li><a href="terms-of-service.html">Terms of Service</a></li>
                    <li><a href="refund-policy.html">Refund Policy</a></li>
                </ul>
                <div class="social-media">
                    <a href="#" aria-label="Follow us on Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Follow us on Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Follow us on LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <p>&copy; 2025 MarineMonks. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

