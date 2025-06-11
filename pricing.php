<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing - MarineMonks</title>
    <meta name="description" content="Choose the perfect plan for your MEO Class 4 exam preparation. Basic and Premium membership options available.">
    <link rel="stylesheet" href="css/consolidated.css">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="canonical" href="https://www.marinemonks.in/pricing.php">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <style>
        .pricing-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            max-width: 800px;
            margin: 0 auto;
        }
        .pricing-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        .pricing-card.premium {
            border: 3px solid var(--primary-color);
            transform: scale(1.05);
        }
        .pricing-card.premium::before {
            content: "MOST POPULAR";
            position: absolute;
            top: 20px;
            right: -30px;
            background: var(--primary-color);
            color: white;
            padding: 8px 40px;
            font-size: 0.8rem;
            font-weight: 700;
            transform: rotate(45deg);
        }
        .plan-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }
        .plan-name {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--dark-text);
        }
        .plan-price {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 5px;
        }
        .plan-price .currency {
            font-size: 1.5rem;
            vertical-align: top;
        }
        .plan-period {
            color: #666;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }
        .plan-features {
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }
        .plan-features li {
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            text-align: left;
        }
        .plan-features li:last-child {
            border-bottom: none;
        }
        .plan-features li i {
            color: var(--secondary-color);
            margin-right: 15px;
            font-size: 1.2rem;
            width: 20px;
        }
        .plan-features li.unavailable {
            color: #ccc;
        }
        .plan-features li.unavailable i {
            color: #ccc;
        }
        .plan-btn {
            width: 100%;
            padding: 15px 30px;
            font-size: 1.2rem;
            font-weight: 700;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .plan-btn.basic {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
        }
        .plan-btn.basic:hover {
            background: linear-gradient(135deg, #495057, #343a40);
            transform: translateY(-2px);
        }
        .plan-btn.premium {
            background: linear-gradient(135deg, var(--primary-color), #0056b3);
            color: white;
        }
        .plan-btn.premium:hover {
            background: linear-gradient(135deg, #0056b3, #004085);
            transform: translateY(-2px);
        }
        .faq-section {
            padding: 80px 0;
            background: white;
        }
        .faq-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .faq-item {
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
            border-radius: var(--border-radius);
            overflow: hidden;
        }
        .faq-question {
            padding: 20px;
            background: #f8f9fa;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .faq-question:hover {
            background: #e9ecef;
        }
        .faq-question i {
            transition: transform 0.3s ease;
        }
        .faq-question.active i {
            transform: rotate(180deg);
        }
        .faq-answer {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .faq-answer.active {
            padding: 20px;
            max-height: 200px;
        }
        .guarantee-badge {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            font-weight: 600;
            margin: 30px auto;
            display: inline-flex;
            align-items: center;
            gap: 10px;
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
                    Choose Your Plan
                </div>
                
                <h1 class="animate-fade-in delay-100">Simple <span class="highlight">Pricing</span></h1>
                
                <p class="animate-fade-in delay-200">
                    Start for free or unlock premium features. No hidden fees, cancel anytime.
                </p>
                
                <div class="guarantee-badge animate-fade-in delay-300">
                    <i class="fas fa-shield-alt"></i>
                    30-Day Money Back Guarantee
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section class="pricing-section">
            <div class="container">
                <div class="pricing-grid">
                    <!-- Basic Plan -->
                    <div class="pricing-card animate-on-scroll">
                        <div class="plan-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h2 class="plan-name">Basic Plan</h2>
                        <div class="plan-price">
                            <span class="currency">₹</span>0
                        </div>
                        <div class="plan-period">Forever Free</div>
                        
                        <ul class="plan-features">
                            <li><i class="fas fa-check"></i> Access to Study Materials</li>
                            <li><i class="fas fa-check"></i> Basic Probables</li>
                            <li><i class="fas fa-check"></i> Previous Year Papers</li>
                            <li><i class="fas fa-check"></i> Community Support</li>
                            <li class="unavailable"><i class="fas fa-times"></i> Mock Tests</li>
                            <li class="unavailable"><i class="fas fa-times"></i> Expert Feedback</li>
                            <li class="unavailable"><i class="fas fa-times"></i> Priority Support</li>
                            <li class="unavailable"><i class="fas fa-times"></i> Advanced Analytics</li>
                        </ul>
                        
                        <a href="signup.php" class="plan-btn basic">Get Started Free</a>
                    </div>

                    <!-- Premium Plan -->
                    <div class="pricing-card premium animate-on-scroll delay-200">
                        <div class="plan-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <h2 class="plan-name">Premium Plan</h2>
                        <div class="plan-price">
                            <span class="currency">₹</span>199
                        </div>
                        <div class="plan-period">per month</div>
                        
                        <ul class="plan-features">
                            <li><i class="fas fa-check"></i> Everything in Basic</li>
                            <li><i class="fas fa-check"></i> Unlimited Mock Tests</li>
                            <li><i class="fas fa-check"></i> Expert Feedback & Evaluation</li>
                            <li><i class="fas fa-check"></i> Advanced Study Materials</li>
                            <li><i class="fas fa-check"></i> Priority Support</li>
                            <li><i class="fas fa-check"></i> Performance Analytics</li>
                            <li><i class="fas fa-check"></i> Exclusive Content</li>
                            <li><i class="fas fa-check"></i> Mobile App Access</li>
                        </ul>
                        
                        <button class="plan-btn premium" onclick="becomePremium()">
                            Become a Premium Member
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="faq-section">
            <div class="container">
                <div class="section-title animate-on-scroll">
                    <h2>Frequently Asked Questions</h2>
                    <p>Everything you need to know about our pricing and features</p>
                </div>
                
                <div class="faq-container">
                    <div class="faq-item animate-on-scroll">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>What's included in the Basic plan?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>The Basic plan includes access to study materials, basic probables, previous year papers, and community support. It's perfect for getting started with your MEO Class 4 preparation.</p>
                        </div>
                    </div>

                    <div class="faq-item animate-on-scroll delay-100">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>How do mock tests work in Premium?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Premium members get access to unlimited mock tests with 3-hour timers, expert evaluation, and detailed feedback. You can upload your answer sheets and receive marks and suggestions from certified surveyors.</p>
                        </div>
                    </div>

                    <div class="faq-item animate-on-scroll delay-200">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>Can I cancel my Premium subscription anytime?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Yes, you can cancel your Premium subscription at any time. We also offer a 30-day money-back guarantee if you're not satisfied with the service.</p>
                        </div>
                    </div>

                    <div class="faq-item animate-on-scroll delay-300">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>Is there a mobile app available?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Yes, Premium members get access to our mobile app for iOS and Android, allowing you to study on the go and access all features from your smartphone or tablet.</p>
                        </div>
                    </div>

                    <div class="faq-item animate-on-scroll delay-400">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <span>How quickly will I get feedback on mock tests?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Our certified surveyors typically provide feedback within 24-48 hours of submission. Premium members receive priority evaluation and detailed performance analytics.</p>
                        </div>
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
        function toggleFAQ(element) {
            const answer = element.nextElementSibling;
            const icon = element.querySelector('i');
            
            // Close all other FAQs
            document.querySelectorAll('.faq-question').forEach(q => {
                if (q !== element) {
                    q.classList.remove('active');
                    q.nextElementSibling.classList.remove('active');
                }
            });
            
            // Toggle current FAQ
            element.classList.toggle('active');
            answer.classList.toggle('active');
        }

        function becomePremium() {
            // This is a dummy function for demo purposes
            // In a real implementation, this would integrate with a payment gateway like Razorpay
            alert('This is a demo. In the real implementation, this would redirect to a payment gateway like Razorpay to process the ₹199/month subscription.');
            
            // For demo purposes, you could redirect to a success page or show a modal
            // window.location.href = 'payment-success.php';
        }

        // Add some interactive animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate pricing cards on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'all 0.6s ease';
                observer.observe(el);
            });
        });
    </script>
</body>
</html>

