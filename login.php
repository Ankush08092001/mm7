<?php
session_start();
require_once __DIR__ . "/config/db.php";

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $username;
            header("Location: dashboard/user-dashboard.html"); // Redirect to dashboard
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MarineMonks</title>
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
                    <li><a href="signup.html" class="btn btn-secondary">Sign Up</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="login-page">
        <div class="container">
            <div class="login-form">
                <h2>Login to your account</h2>
                <?php if (!empty($error_message)): ?>
                    <p class="error-message"><?php echo $error_message; ?></p>
                <?php endif; ?>
                <form action="login.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Your username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
                <p>Don't have an account? <a href="signup.html">Sign Up</a></p>
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

