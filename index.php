<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarineMonks - Pass Your MEO Class 4 on the First Try</title>
    <link rel="stylesheet" href="css/consolidated.css">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="canonical" href="https://www.marinemonks.in/index.html">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <!-- Meta tags for SEO and social sharing -->
    <meta name="description" content="MarineMonks - The most trusted educational platform for marine engineers preparing for MEO Class 4 exams. Get access to curated study materials, mock tests, and more.">
    <meta name="keywords" content="MEO Class 4, Marine Engineering, Marine Exams, Study Material, Mock Tests">
    <meta property="og:title" content="MarineMonks - Pass Your MEO Class 4 on the First Try">
    <meta property="og:description" content="The most trusted educational platform for marine engineers preparing for MEO Class 4 exams.">
    <meta property="og:type" content="website">
<script src="js/navigation.js" defer></script>
</head>
<body>
    <!-- Skip to content link for accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>
    
    <!-- Header -->
    <header role="banner">
        <div class="container">
            <nav class="navbar" role="navigation" aria-label="Main navigation">
                <a href="index.php" class="logo" aria-label="MarineMonks Home">
                    <img src="images/logo/logo-new.webp" alt="MarineMonks Logo" width="50" height="50" loading="lazy">
                    <span class="logo-text">MarineMonks</span>
                </a>
                
                <div class="mobile-menu-toggle" aria-expanded="false" aria-label="Toggle navigation menu" aria-controls="nav-links">
                    <i class="fas fa-bars"></i>
                </div>
                
                <ul class="nav-links" id="nav-links">
                    <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="study-material.php">Study Material</a></li>
                    <li><a href="mock-tests.php">Mock Tests</a></li>
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
                India's Premier Marine Engineering Platform
            </div>
            
            <h1 class="animate-fade-in delay-100">
                Master Your <span class="highlight">MEO Class 4 Exams</span>
            </h1>
            
            <p class="animate-fade-in delay-300">
                The most trusted educational platform for marine engineers. Get access to curated study materials, function-wise mock tests, video lectures, and surveyor-approved content.
            </p>
            
            <div class="hero-buttons animate-fade-in delay-400">
                <a href="signup.php" class="btn btn-primary">Start Learning Today</a>
            </div>
            
            <div class="stats">
                <div class="stat-item animate-fade-in delay-100">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Study Materials</div>
                </div>
                
                <div class="stat-item animate-fade-in delay-200">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Mock Questions</div>
                </div>
                
                <div class="stat-item animate-fade-in delay-300">
                    <div class="stat-number">95%</div>
                    <div class="stat-label">Success Rate</div>
                </div>
            </div>
            
            <div class="scroll-indicator">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-title">
                <h2>Everything You Need to <span class="highlight">Succeed</span></h2>
                <p>From comprehensive study materials to mock tests - we've got you covered.</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3>Study Materials</h3>
                    <p>Surveyor-approved PDFs organized by subject. Everything in one place, nothing scattered.</p>
                </div>
                
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h3>Mock Tests</h3>
                    <p>Function-wise mock tests that mirror real exam patterns. Practice until you're confident.</p>
                </div>
                
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-microphone"></i>
                    </div>
                    <h3>Oral Exam Prep <span class="coming-soon">Coming Soon</span></h3>
                    <p>Live oral simulations with expert feedback. Walk into your viva with confidence.</p>
                </div>
                
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3>Previous Papers</h3>
                    <p>Access to previous years' question papers with detailed solutions and explanations.</p>
                </div>
                
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>24/7 Support</h3>
                    <p>Live doubt-clearing sessions anytime. No question goes unanswered, even at 2 AM.</p>
                </div>
                
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3>Quick Revision</h3>
                    <p>Probables section with images and key points. Perfect for last-minute revision.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pain Points Section -->
    <section class="pain-points" id="challenges">
        <div class="container">
            <div class="section-title">
                <h2>Feeling Stressed? You're Not Alone.</h2>
                <p>Countless cadets say, "I don't know what to study!" or "No one answers my doubts late at night."</p>
            </div>
            
            <div class="pain-container">
                <div class="pain-content">
                    <div class="pain-box">
                        <h3><i class="fas fa-exclamation-triangle"></i> The Real Pain:</h3>
                        <p>Constant <strong>fear of failing</strong> and delaying your marine career. You study hard but end up <strong>confused and nervous</strong> about oral interviews.</p>
                    </div>
                    
                    <div class="struggles-list">
                        <h3><i class="fas fa-book"></i> Common Struggles:</h3>
                        <ul>
                            <li>"I feel lost with all these scattered books and notes"</li>
                            <li>"There's no one to ask when I'm stuck at 2 AM"</li>
                            <li>"The oral exam is terrifying, I need practice!"</li>
                        </ul>
                    </div>
                    
                    <div class="time-pressure">
                        <h3><i class="fas fa-clock"></i> Time Pressure:</h3>
                        <p>Every failed attempt means <strong>months of delay</strong> in starting your marine engineering career.</p>
                    </div>
                </div>
                
                <div class="pain-image">
                    <img src="images/image2" alt="Stressed marine engineering student looking overwhelmed by books" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- Solution Section -->
    <section class="solution" id="solution">
        <div class="container">
            <h2>One Platform, <span>Complete Solution</span></h2>
            <p>MarineMonks brings everything together. Forget juggling apps; <a href="#features">focus on learning</a>, not logistics.</p>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="benefits" id="benefits">
        <div class="container">
            <div class="section-title">
                <h2>Why Choose <span class="highlight">MarineMonks</span>?</h2>
                <p>Our platform is designed specifically for MEO Class 4 aspirants.</p>
            </div>
            
            <div class="benefits-container">
                <div class="benefits-content">
                    <div class="benefit-item animate-on-scroll">
                        <div class="benefit-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <div class="benefit-text">
                            <h3>Pass in First Attempt</h3>
                            <p>Tailored lessons build your confidence. Cadets who use MarineMonks often "clear it in the 1st attempt".</p>
                        </div>
                    </div>
                    
                    <div class="benefit-item animate-on-scroll">
                        <div class="benefit-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="benefit-text">
                            <h3>Clarity & Focus</h3>
                            <p>No more confusion about what to study. Our structured approach guides you through every topic.</p>
                        </div>
                    </div>
                    
                    <div class="benefit-item animate-on-scroll">
                        <div class="benefit-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="benefit-text">
                            <h3>Save Time</h3>
                            <p>Stop wasting hours searching for materials. Everything is organized and ready for you.</p>
                        </div>
                    </div>
                </div>
                
                <div class="benefits-image">
                    <img src="images/image1" alt="Confident marine engineering student ready for exams" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- Success Rate Section -->
    <section class="success-rate" id="success">
        <div class="container">
            <div class="success-rate-inner">
                <div class="success-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <h2>95% Success Rate</h2>
                <p>Our students consistently pass their MEO Class 4 exams on the first attempt. Join them and accelerate your marine engineering career.</p>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials" id="testimonials">
        <div class="container">
            <div class="section-title">
                <h2>What Our <span class="highlight">Students Say</span></h2>
                <p>Don't just take our word for it. Hear from cadets who've been in your shoes.</p>
            </div>
            
            <div class="testimonials-grid">
                <div class="testimonial-card animate-on-scroll">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">R</div>
                        <div class="testimonial-info">
                            <h4>Rahul Singh</h4>
                            <p>Passed MEO Class 4 in first attempt</p>
                        </div>
                    </div>
                    <div class="testimonial-text">
                        "The structured approach made all the difference. I knew exactly what to study and when. The mock tests were spot on!"
                    </div>
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                
                <div class="testimonial-card animate-on-scroll">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">P</div>
                        <div class="testimonial-info">
                            <h4>Priya Patel</h4>
                            <p>Cleared all functions in one go</p>
                        </div>
                    </div>
                    <div class="testimonial-text">
                        "With their live mock orals, I walked into the exam confident. The 24/7 support saved me so many times!"
                    </div>
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                
                <div class="testimonial-card animate-on-scroll">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">A</div>
                        <div class="testimonial-info">
                            <h4>Arjun Menon</h4>
                            <p>Now working as 4th Engineer</p>
                        </div>
                    </div>
                    <div class="testimonial-text">
                        "The probables section was a lifesaver for last-minute revision. Everything important was highlighted exactly as it appeared in the exam."
                    </div>
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Ready to Ace Your MEO Class 4?</h2>
            <p>Join MarineMonks today and unlock your full potential. Start your journey to success now!</p>
            <a href="signup.php" class="btn btn-primary">Get Started Today</a>
        </div>
    </section>
    </main>

    <!-- Footer -->
    <footer role="contentinfo">
        <div class="container">
            <div class="footer-links">
                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="study-material.php">Study Material</a></li>
                        <li><a href="mock-tests.php">Mock Tests</a></li>
                        <li><a href="papers.html">Papers</a></li>
                        <li><a href="probables.php">Probables</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Legal</h3>
                    <ul>
                        <li><a href="privacy-policy.html">Privacy Policy</a></li>
                        <li><a href="terms-of-service.html">Terms of Service</a></li>
                        <li><a href="refund-policy.html">Refund Policy</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Connect With Us</h3>
                    <div class="social-icons">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 MarineMonks. All rights reserved.</p>
                <p>Made with <a href="https://www.google.com/search?q=Manus+Create+my+website" target="_blank">Manus Create my website</a></p>
            </div>
        </div>
    </footer>

    <script src="js/main.js"></script>
    <script src="js/animations.js"></script>
</body>
</html>

