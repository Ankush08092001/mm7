<?php
session_start();
require_once __DIR__ . "/config/db.php";

// Check if user is premium member
$is_premium = false;
if (isset($_SESSION["user_id"])) {
    $stmt = $conn->prepare("SELECT is_premium_member FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $is_premium = $row["is_premium_member"];
    }
    $stmt->close();
}

// Handle answer sheet submission
$submission_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_answer_sheet"])) {
    if (!isset($_SESSION["user_id"])) {
        $submission_message = "Please login to submit answer sheets.";
    } elseif (!$is_premium) {
        $submission_message = "Premium membership required to submit answer sheets.";
    } else {
        $test_id = $_POST["test_id"];
        
        // Handle file upload
        $target_dir = "uploads/answersheets/";
        $file_name = $_SESSION["user_id"] . "_" . $test_id . "_" . time() . "_" . basename($_FILES["answer_sheet"]["name"]);
        $target_file = $target_dir . $file_name;
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allow certain file formats
        if($fileType != "pdf" && $fileType != "jpg" && $fileType != "jpeg" && $fileType != "png") {
            $submission_message = "Sorry, only PDF, JPG, JPEG & PNG files are allowed.";
            $uploadOk = 0;
        }

        // Check file size (5MB max)
        if ($_FILES["answer_sheet"]["size"] > 5000000) {
            $submission_message = "Sorry, your file is too large. Maximum 5MB allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["answer_sheet"]["tmp_name"], $target_file)) {
                $stmt = $conn->prepare("INSERT INTO answersheets (user_id, test_id, file_path, status) VALUES (?, ?, ?, 'pending')");
                $stmt->bind_param("iis", $_SESSION["user_id"], $test_id, $file_name);

                if ($stmt->execute()) {
                    $submission_message = "Your answer sheet is being checked by a certified surveyor.";
                } else {
                    $submission_message = "Error saving submission: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $submission_message = "Sorry, there was an error uploading your file.";
            }
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mock Tests - MarineMonks</title>
    <meta name="description" content="Practice MEO Class 4 mock tests with MarineMonks. Function-wise tests with timer and expert evaluation.">
    <link rel="stylesheet" href="css/consolidated.css">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="canonical" href="https://www.marinemonks.in/mock-tests.php">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <style>
        .mock-test-tabs {
            display: flex;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 30px;
            overflow: hidden;
        }
        .mock-test-tab {
            flex: 1;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .mock-test-tab.active {
            background-color: var(--primary-color);
            color: white;
        }
        .mock-test-tab:not(.active) {
            background-color: #f8f9fa;
            color: var(--dark-text);
        }
        .mock-test-tab:not(.active):hover {
            background-color: #e9ecef;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .test-difficulty-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        .test-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 25px;
            transition: all 0.3s ease;
            position: relative;
        }
        .test-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .test-card h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: var(--primary-color);
        }
        .test-card .difficulty-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .test-card .difficulty-badge.easy {
            background-color: #d4edda;
            color: #155724;
        }
        .test-card .difficulty-badge.medium {
            background-color: #fff3cd;
            color: #856404;
        }
        .test-card .difficulty-badge.hard {
            background-color: #f8d7da;
            color: #721c24;
        }
        .test-card .test-info {
            margin-bottom: 20px;
        }
        .test-card .test-info p {
            margin: 5px 0;
            color: #666;
        }
        .test-card .start-test-btn {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .test-card .start-test-btn:hover {
            background-color: #0056b3;
        }
        .test-card .start-test-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        /* Premium Lock Overlay */
        .premium-lock-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
            border-radius: var(--border-radius);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .premium-lock-overlay .lock-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #ffd700;
        }
        .premium-lock-overlay h4 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: white;
        }
        .premium-lock-overlay p {
            font-size: 1rem;
            margin-bottom: 20px;
            opacity: 0.9;
        }
        .premium-unlock-btn {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            color: #333;
            border: none;
            padding: 12px 25px;
            font-size: 1rem;
            font-weight: 700;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .premium-unlock-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
        }
        /* Test Interface */
        .test-interface {
            display: none;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 30px;
            margin-bottom: 30px;
        }
        .test-interface.active {
            display: block;
        }
        .test-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        .test-timer {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        .question-counter {
            font-size: 1.2rem;
            color: #666;
        }
        .test-questions {
            margin-bottom: 30px;
        }
        .question {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: var(--border-radius);
        }
        .question h4 {
            margin-bottom: 15px;
            color: var(--dark-text);
        }
        .answer-upload {
            background-color: #f8f9fa;
            border-radius: var(--border-radius);
            padding: 25px;
            margin-top: 30px;
        }
        .answer-upload h3 {
            margin-bottom: 15px;
            color: var(--primary-color);
        }
        .file-upload-area {
            border: 2px dashed #ccc;
            border-radius: var(--border-radius);
            padding: 30px;
            text-align: center;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        .file-upload-area:hover {
            border-color: var(--primary-color);
            background-color: rgba(0, 102, 204, 0.05);
        }
        .file-upload-area input[type="file"] {
            display: none;
        }
        .file-upload-label {
            cursor: pointer;
            color: var(--primary-color);
            font-weight: 600;
        }
        .submit-answer-btn {
            width: 100%;
            padding: 15px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .submit-answer-btn:hover {
            background-color: #00a382;
        }
        .message {
            padding: 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            font-weight: 600;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
    <script src="js/navigation.js" defer></script>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav class="navbar">
                <a href="index.php" class="logo" aria-label="MarineMonks Home">
                    <img src="images/logo/logo-new.webp" alt="MarineMonks Logo" width="50" height="50" loading="lazy">
                    <span class="logo-text">MarineMonks</span>
                </a>
                
                <div class="mobile-menu-toggle" aria-expanded="false" aria-label="Toggle navigation menu">
                    <i class="fas fa-bars"></i>
                </div>
                
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="study-material.php">Study Material</a></li>
                    <li><a href="mock-tests.php" class="active">Mock Tests</a></li>
                    <li><a href="papers.html">Papers</a></li>
                    <li><a href="probables.php">Probables</a></li>
                </ul>
                
                <div class="auth-buttons">
                    <?php if (isset($_SESSION["user_id"])): ?>
                        <a href="logout.php" class="btn btn-outline">Logout</a>
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
                    Practice Makes Perfect
                </div>
                
                <h1 class="animate-fade-in delay-100">Mock <span class="highlight">Tests</span></h1>
                
                <p class="animate-fade-in delay-200">
                    Function-wise mock tests that mirror real exam patterns. Practice with 3-hour timer and get expert feedback.
                </p>
                
                <div class="scroll-indicator">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </section>

        <!-- Mock Tests Section -->
        <section class="mock-tests">
            <div class="container">
                <?php if (!empty($submission_message)): ?>
                    <div class="message <?php echo (strpos($submission_message, 'Error') !== false || strpos($submission_message, 'Sorry') !== false) ? 'error' : 'success'; ?>">
                        <?php echo $submission_message; ?>
                    </div>
                <?php endif; ?>

                <!-- Tabs -->
                <div class="mock-test-tabs animate-on-scroll animated">
                    <div class="mock-test-tab active" onclick="switchTab('written')">
                        <i class="fas fa-pen"></i> Written Mock Tests
                    </div>
                    <div class="mock-test-tab" onclick="switchTab('orals')">
                        <i class="fas fa-microphone"></i> Orals Mock Tests
                    </div>
                </div>

                <!-- Written Mock Tests -->
                <div id="written-content" class="tab-content active">
                    <div class="test-difficulty-grid">
                        <div class="test-card">
                            <h3>Easy Level</h3>
                            <div class="difficulty-badge easy">Beginner Friendly</div>
                            <div class="test-info">
                                <p><i class="fas fa-clock"></i> Duration: 3 hours</p>
                                <p><i class="fas fa-question-circle"></i> Questions: 9</p>
                                <p><i class="fas fa-chart-line"></i> Difficulty: Easy</p>
                            </div>
                            <?php if (!$is_premium): ?>
                                <div class="premium-lock-overlay">
                                    <i class="fas fa-lock lock-icon"></i>
                                    <h4>Premium Feature</h4>
                                    <p>This feature is for Premium Members only.</p>
                                    <a href="pricing.php" class="premium-unlock-btn">
                                        <i class="fas fa-crown"></i> Become Premium
                                    </a>
                                </div>
                            <?php endif; ?>
                            <button class="start-test-btn" onclick="startTest('easy')" <?php echo !$is_premium ? 'disabled' : ''; ?>>
                                Start Easy Test
                            </button>
                        </div>

                        <div class="test-card">
                            <h3>Medium Level</h3>
                            <div class="difficulty-badge medium">Intermediate</div>
                            <div class="test-info">
                                <p><i class="fas fa-clock"></i> Duration: 3 hours</p>
                                <p><i class="fas fa-question-circle"></i> Questions: 9</p>
                                <p><i class="fas fa-chart-line"></i> Difficulty: Medium</p>
                            </div>
                            <?php if (!$is_premium): ?>
                                <div class="premium-lock-overlay">
                                    <i class="fas fa-lock lock-icon"></i>
                                    <h4>Premium Feature</h4>
                                    <p>This feature is for Premium Members only.</p>
                                    <a href="pricing.php" class="premium-unlock-btn">
                                        <i class="fas fa-crown"></i> Become Premium
                                    </a>
                                </div>
                            <?php endif; ?>
                            <button class="start-test-btn" onclick="startTest('medium')" <?php echo !$is_premium ? 'disabled' : ''; ?>>
                                Start Medium Test
                            </button>
                        </div>

                        <div class="test-card">
                            <h3>Hard Level</h3>
                            <div class="difficulty-badge hard">Advanced</div>
                            <div class="test-info">
                                <p><i class="fas fa-clock"></i> Duration: 3 hours</p>
                                <p><i class="fas fa-question-circle"></i> Questions: 9</p>
                                <p><i class="fas fa-chart-line"></i> Difficulty: Hard</p>
                            </div>
                            <?php if (!$is_premium): ?>
                                <div class="premium-lock-overlay">
                                    <i class="fas fa-lock lock-icon"></i>
                                    <h4>Premium Feature</h4>
                                    <p>This feature is for Premium Members only.</p>
                                    <a href="pricing.php" class="premium-unlock-btn">
                                        <i class="fas fa-crown"></i> Become Premium
                                    </a>
                                </div>
                            <?php endif; ?>
                            <button class="start-test-btn" onclick="startTest('hard')" <?php echo !$is_premium ? 'disabled' : ''; ?>>
                                Start Hard Test
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Orals Mock Tests -->
                <div id="orals-content" class="tab-content">
                    <div class="test-difficulty-grid">
                        <div class="test-card">
                            <h3>Function 3</h3>
                            <div class="difficulty-badge easy">Operation & Care</div>
                            <div class="test-info">
                                <p><i class="fas fa-clock"></i> Duration: 30 minutes</p>
                                <p><i class="fas fa-question-circle"></i> Questions: 5</p>
                                <p><i class="fas fa-ship"></i> Focus: Ship Operations</p>
                            </div>
                            <?php if (!$is_premium): ?>
                                <div class="premium-lock-overlay">
                                    <i class="fas fa-lock lock-icon"></i>
                                    <h4>Premium Feature</h4>
                                    <p>This feature is for Premium Members only.</p>
                                    <a href="pricing.php" class="premium-unlock-btn">
                                        <i class="fas fa-crown"></i> Become Premium
                                    </a>
                                </div>
                            <?php endif; ?>
                            <button class="start-test-btn" onclick="startTest('function3')" <?php echo !$is_premium ? 'disabled' : ''; ?>>
                                Start Function 3
                            </button>
                        </div>

                        <div class="test-card">
                            <h3>Function 4B</h3>
                            <div class="difficulty-badge medium">Marine Engineering</div>
                            <div class="test-info">
                                <p><i class="fas fa-clock"></i> Duration: 30 minutes</p>
                                <p><i class="fas fa-question-circle"></i> Questions: 5</p>
                                <p><i class="fas fa-cogs"></i> Focus: Engineering</p>
                            </div>
                            <?php if (!$is_premium): ?>
                                <div class="premium-lock-overlay">
                                    <i class="fas fa-lock lock-icon"></i>
                                    <h4>Premium Feature</h4>
                                    <p>This feature is for Premium Members only.</p>
                                    <a href="pricing.php" class="premium-unlock-btn">
                                        <i class="fas fa-crown"></i> Become Premium
                                    </a>
                                </div>
                            <?php endif; ?>
                            <button class="start-test-btn" onclick="startTest('function4b')" <?php echo !$is_premium ? 'disabled' : ''; ?>>
                                Start Function 4B
                            </button>
                        </div>

                        <div class="test-card">
                            <h3>Function 5 & 6</h3>
                            <div class="difficulty-badge hard">Electrical & Maintenance</div>
                            <div class="test-info">
                                <p><i class="fas fa-clock"></i> Duration: 30 minutes</p>
                                <p><i class="fas fa-question-circle"></i> Questions: 5</p>
                                <p><i class="fas fa-bolt"></i> Focus: Electrical Systems</p>
                            </div>
                            <?php if (!$is_premium): ?>
                                <div class="premium-lock-overlay">
                                    <i class="fas fa-lock lock-icon"></i>
                                    <h4>Premium Feature</h4>
                                    <p>This feature is for Premium Members only.</p>
                                    <a href="pricing.php" class="premium-unlock-btn">
                                        <i class="fas fa-crown"></i> Become Premium
                                    </a>
                                </div>
                            <?php endif; ?>
                            <button class="start-test-btn" onclick="startTest('function56')" <?php echo !$is_premium ? 'disabled' : ''; ?>>
                                Start Function 5 & 6
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Test Interface -->
                <div id="test-interface" class="test-interface">
                    <div class="test-header">
                        <h2 id="test-title">Mock Test</h2>
                        <div class="test-timer" id="timer">03:00:00</div>
                        <div class="question-counter">
                            <span id="current-question">1</span> / <span id="total-questions">9</span>
                        </div>
                    </div>

                    <div class="test-questions" id="test-questions">
                        <!-- Questions will be loaded here -->
                    </div>

                    <div class="answer-upload">
                        <h3>Upload Your Answer Sheet</h3>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="test_id" id="test_id" value="">
                            <div class="file-upload-area">
                                <label for="answer_sheet" class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                                    Click to upload your answer sheet (PDF, JPG, PNG)
                                </label>
                                <input type="file" id="answer_sheet" name="answer_sheet" accept=".pdf,.jpg,.jpeg,.png" required>
                            </div>
                            <button type="submit" name="submit_answer_sheet" class="submit-answer-btn">
                                Submit Answer Sheet
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container footer-grid">
            <div class="footer-col">
                <a href="index.php" class="footer-logo">
                    <img src="images/logo/logo-new.webp" alt="MarineMonks Logo" width="40" height="40" loading="lazy">
                    <span>MarineMonks</span>
                </a>
                <p>The most trusted educational platform for marine engineers preparing for MEO Class 4 exams.</p>
                <div class="social-icons">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="study-material.php">Study Material</a></li>
                    <li><a href="mock-tests.php">Mock Tests</a></li>
                    <li><a href="papers.html">Papers</a></li>
                    <li><a href="probables.php">Probables</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Legal</h3>
                <ul>
                    <li><a href="privacy-policy.html">Privacy Policy</a></li>
                    <li><a href="terms-of-service.html">Terms of Service</a></li>
                    <li><a href="refund-policy.html">Refund Policy</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Contact</h3>
                <p>Email: support@marinemonks.in</p>
                <p>Phone: +91 9876543210</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 MarineMonks. All rights reserved.</p>
            <p>Made with <a href="https://www.google.com/search?q=Manus+Create+my+website" target="_blank">Manus Create my website</a></p>
        </div>
    </footer>

    <script src="js/main.js"></script>
    <script src="js/animations.js"></script>
    <script>
        let currentTab = 'written';
        let testTimer;
        let timeRemaining = 10800; // 3 hours in seconds

        function switchTab(tab) {
            currentTab = tab;
            
            // Update tab appearance
            document.querySelectorAll('.mock-test-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            event.target.classList.add('active');
            document.getElementById(tab + '-content').classList.add('active');
        }

        function startTest(testType) {
            <?php if (!$is_premium): ?>
                alert('Premium membership required to access mock tests.');
                return;
            <?php endif; ?>

            // Hide test cards and show test interface
            document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
            document.getElementById('test-interface').classList.add('active');
            
            // Set test title and ID
            document.getElementById('test-title').textContent = `${testType.charAt(0).toUpperCase() + testType.slice(1)} Mock Test`;
            document.getElementById('test_id').value = testType;
            
            // Load questions for the test
            loadTestQuestions(testType);
            
            // Start timer
            startTimer();
        }

        function loadTestQuestions(testType) {
            // Sample questions - in real implementation, these would come from the database
            const questions = [
                "1. Explain the working principle of a marine diesel engine.",
                "2. Describe the safety procedures for entering an enclosed space.",
                "3. What are the main components of a ship's electrical system?",
                "4. Explain the process of ballast water management.",
                "5. Describe the function of a turbocharger in marine engines.",
                "6. What are the requirements for fire safety on ships?",
                "7. Explain the working of a ship's steering gear system.",
                "8. Describe the process of fuel oil treatment on ships.",
                "9. What are the main types of marine pumps and their applications?"
            ];

            const questionsContainer = document.getElementById('test-questions');
            let html = '';
            
            questions.forEach((question, index) => {
                html += `
                    <div class="question">
                        <h4>Question ${index + 1}</h4>
                        <p>${question}</p>
                    </div>
                `;
            });
            
            questionsContainer.innerHTML = html;
            document.getElementById('total-questions').textContent = questions.length;
        }

        function startTimer() {
            timeRemaining = 10800; // Reset to 3 hours
            updateTimerDisplay();
            
            testTimer = setInterval(() => {
                timeRemaining--;
                updateTimerDisplay();
                
                if (timeRemaining <= 0) {
                    clearInterval(testTimer);
                    alert('Time is up! Please submit your answer sheet.');
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const hours = Math.floor(timeRemaining / 3600);
            const minutes = Math.floor((timeRemaining % 3600) / 60);
            const seconds = timeRemaining % 60;
            
            const display = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            document.getElementById('timer').textContent = display;
            
            // Change color when time is running low
            if (timeRemaining < 600) { // Less than 10 minutes
                document.getElementById('timer').style.color = '#dc3545';
            } else if (timeRemaining < 1800) { // Less than 30 minutes
                document.getElementById('timer').style.color = '#ffc107';
            }
        }

        // File upload preview
        document.getElementById('answer_sheet').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const label = document.querySelector('.file-upload-label');
                label.innerHTML = `
                    <i class="fas fa-file-alt" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                    Selected: ${file.name}
                `;
            }
        });
    </script>
</body>
</html>

