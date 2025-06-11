<?php
require_once 'config/db.php';

// Get all probables, grouped by year
$conn = getDBConnection();
$probables = [];

if ($conn) {
    $query = "SELECT * FROM probables ORDER BY year DESC, title ASC";
    $result = $conn->query($query);
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $year = $row['year'];
            if (!isset($probables[$year])) {
                $probables[$year] = [];
            }
            $probables[$year][] = $row;
        }
    }
    $conn->close();
}

// Handle file download
if (isset($_GET['download']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn = getDBConnection();
    
    if ($conn) {
        $stmt = $conn->prepare("SELECT file_path FROM probables WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $file_path = $row['file_path'];
            
            // Update download count
            $stmt = $conn->prepare("UPDATE probables SET downloads = downloads + 1 WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            // Log download in analytics if user is logged in
            if (isLoggedIn()) {
                $user_id = $_SESSION['user_id'];
                $stmt = $conn->prepare("INSERT INTO analytics (user_id, file_type, file_id, action) VALUES (?, 'probable', ?, 'download')");
                $stmt->bind_param("ii", $user_id, $id);
                $stmt->execute();
            }
            
            // Serve file
            if (file_exists($file_path)) {
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
                readfile($file_path);
                exit();
            }
        }
        $stmt->close();
        $conn->close();
    }
}

// Handle file view
if (isset($_GET['view']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn = getDBConnection();
    
    if ($conn) {
        // Update view count
        $stmt = $conn->prepare("UPDATE probables SET views = views + 1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Log view in analytics if user is logged in
        if (isLoggedIn()) {
            $user_id = $_SESSION['user_id'];
            $stmt = $conn->prepare("INSERT INTO analytics (user_id, file_type, file_id, action) VALUES (?, 'probable', ?, 'view')");
            $stmt->bind_param("ii", $user_id, $id);
            $stmt->execute();
        }
        
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
    <title>Probables - MarineMonks</title>
    <link rel="stylesheet" href="css/consolidated.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <style>
        .probable-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 20px;
            margin-bottom: 20px;
            transition: var(--transition);
        }
        
        .probable-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .probable-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .probable-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-text);
        }
        
        .probable-stats {
            display: flex;
            gap: 15px;
            color: #666;
            font-size: 0.9rem;
        }
        
        .probable-preview {
            width: 100%;
            height: 300px;
            border: 1px solid #eee;
            margin-bottom: 15px;
        }
        
        .probable-actions {
            display: flex;
            gap: 10px;
        }
        
        .coming-soon-badge {
            background-color: #fbbf24;
            color: #92400e;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .year-section {
            margin-bottom: 40px;
        }
        
        .year-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
        }
    </style>
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
                    <li><a href="probables.php" class="active">Probables</a></li>
                </ul>
                
                <div class="auth-buttons">
                    <?php if (isLoggedIn()): ?>
                        <a href="user/dashboard.php" class="btn btn-outline">Dashboard</a>
                        <a href="logout.php" class="btn btn-primary">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline">Login</a>
                        <a href="signup.php" class="btn btn-primary">Sign Up</a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero" id="main-content">
            <div class="container">
                <div class="badge animate-fade-in">
                    Free Resources
                </div>
                
                <h1 class="animate-fade-in delay-100">Probable <span class="highlight">Questions</span></h1>
                
                <p class="animate-fade-in delay-200">
                    Access our collection of probable questions from previous years. Download PDFs and prepare for your MEO Class 4 exams.
                </p>
            </div>
        </section>

        <!-- Probables Section -->
        <section class="features">
            <div class="container">
                <?php foreach ($probables as $year => $yearProbables): ?>
                    <div class="year-section">
                        <h2 class="year-title"><?php echo htmlspecialchars($year); ?></h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <?php foreach ($yearProbables as $probable): ?>
                                <div class="probable-card">
                                    <div class="probable-header">
                                        <h3 class="probable-title"><?php echo htmlspecialchars($probable['title']); ?></h3>
                                        <?php if ($probable['coming_soon']): ?>
                                            <span class="coming-soon-badge">Coming Soon</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="probable-stats">
                                        <span><i class="fas fa-eye"></i> <?php echo $probable['views']; ?> views</span>
                                        <span><i class="fas fa-download"></i> <?php echo $probable['downloads']; ?> downloads</span>
                                    </div>
                                    
                                    <?php if (!$probable['coming_soon']): ?>
                                        <div class="probable-preview">
                                            <iframe src="<?php echo htmlspecialchars($probable['file_path']); ?>" width="100%" height="100%"></iframe>
                                        </div>
                                        
                                        <div class="probable-actions">
                                            <a href="?view=1&id=<?php echo $probable['id']; ?>" class="btn btn-outline" target="_blank">
                                                <i class="fas fa-eye"></i> Preview
                                            </a>
                                            <a href="?download=1&id=<?php echo $probable['id']; ?>" class="btn btn-primary">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
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
                        <li><a href="probables.php">Probables</a></li>
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