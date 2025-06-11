<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Probables - MarineMonks</title>
    <meta name="description" content="Review probable topics for the MEO Class 4 exam with MarineMonks. Quick revision notes, key points, and images for last-minute preparation.">
    <link rel="stylesheet" href="css/consolidated.css">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="canonical" href="https://www.marinemonks.in/probables.php">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <style>
        /* Custom styles for the Probables page redesign to match homepage */
        .page-content {
            padding: 60px 0;
            background-color: var(--light-bg);
        }

        .search-filter-bar {
            display: flex;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .search-filter-bar input[type="text"],
        .search-filter-bar select {
            flex: 1;
            min-width: 200px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-family: inherit;
            box-shadow: var(--box-shadow);
            transition: all 0.3s ease;
        }

        .search-filter-bar input[type="text"]:focus,
        .search-filter-bar select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.2);
            outline: none;
        }

        .year-section {
            margin-bottom: 25px;
            border: 1px solid #e0e0e0;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            background-color: #fff;
        }

        .year-header {
            background-color: #f8f9fa;
            padding: 18px 25px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--dark-text);
            border-bottom: 1px solid #e0e0e0;
            transition: background-color 0.3s ease;
        }

        .year-header:hover {
            background-color: #e9ecef;
        }

        .year-header .arrow {
            transition: transform 0.3s ease;
        }

        .year-header.collapsed .arrow {
            transform: rotate(-90deg);
        }

        .year-content {
            padding: 20px 25px;
            display: none; /* Hidden by default */
        }

        .year-content.expanded {
            display: block;
        }

        .file-item {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px dashed #e9ecef;
        }

        .file-item:last-child {
            border-bottom: none;
        }

        .file-info {
            flex-grow: 1;
            margin-bottom: 10px;
        }

        .file-info h4 {
            margin: 0;
            font-size: 1.2rem;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .file-meta {
            font-size: 0.9rem;
            color: #6c757d;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .file-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .file-tags {
            margin-top: 10px;
        }

        .tag {
            display: inline-block;
            background-color: #e0f7fa;
            color: var(--primary-color);
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            margin-right: 8px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .coming-soon-tag {
            background-color: #ffc107;
            color: #343a40;
            font-weight: 700;
        }

        .file-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .file-actions button {
            padding: 10px 18px;
            border-radius: var(--border-radius);
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 1rem;
        }

        .file-actions button.preview-btn {
            background-color: var(--secondary-color);
            color: white;
        }

        .file-actions button.preview-btn:hover {
            background-color: #00a080;
            transform: translateY(-2px);
        }

        .file-actions button.download-btn {
            background-color: var(--primary-color);
            color: white;
        }

        .file-actions button.download-btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .file-actions svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }

        /* PDF Preview Modal */
        .pdf-modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.8); /* Black w/ opacity */
            justify-content: center;
            align-items: center;
        }
        .pdf-modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            height: 90%;
            display: flex;
            flex-direction: column;
            border-radius: 8px;
        }
        .pdf-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            margin-bottom: 15px;
        }
        .pdf-modal-header h3 {
            margin: 0;
            color: #333;
        }
        .pdf-modal-close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .pdf-modal-close:hover,
        .pdf-modal-close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
        .pdf-viewer-frame {
            flex-grow: 1;
            border: none;
            width: 100%;
            height: 100%;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .search-filter-bar {
                flex-direction: column;
            }
            .file-item {
                flex-direction: column;
                align-items: flex-start;
            }
            .file-actions {
                width: 100%;
                justify-content: flex-end;
            }
            .file-actions button {
                margin-left: 0;
                margin-right: 5px;
            }
        }

        /* Latest Probable Card */
        .latest-probable-card {
            background-color: var(--primary-color);
            color: white;
            padding: 30px;
            border-radius: var(--border-radius);
            margin-bottom: 40px;
            box-shadow: var(--box-shadow);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
        }

        .latest-probable-card h2 {
            color: white;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .latest-probable-card .meta-info {
            font-size: 1.1rem;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .latest-probable-card .btn-group {
            display: flex;
            gap: 15px;
        }

        .latest-probable-card .btn-group .btn {
            background-color: white;
            color: var(--primary-color);
            border: 2px solid white;
            padding: 12px 25px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
        }

        .latest-probable-card .btn-group .btn:hover {
            background-color: var(--secondary-color);
            color: white;
            border-color: var(--secondary-color);
        }

        /* Premium Lock Overlay */
        .premium-lock-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
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
            font-size: 4rem;
            margin-bottom: 20px;
            color: #ffd700;
        }

        .premium-lock-overlay h3 {
            font-size: 1.8rem;
            margin-bottom: 15px;
            color: white;
        }

        .premium-lock-overlay p {
            font-size: 1.1rem;
            margin-bottom: 25px;
            opacity: 0.9;
            max-width: 400px;
        }

        .premium-unlock-btn {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            color: #333;
            border: none;
            padding: 15px 30px;
            font-size: 1.2rem;
            font-weight: 700;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
        }

        .premium-unlock-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
            background: linear-gradient(135deg, #ffed4e, #ffd700);
        }

        .premium-unlock-btn i {
            font-size: 1.1rem;
        }

        /* Hidden state for premium content */
        .premium-content {
            display: none;
        }

        .premium-content.show {
            display: flex;
        }

        @media (max-width: 768px) {
            .latest-probable-card h2 {
                font-size: 2rem;
            }
            .latest-probable-card .btn-group {
                flex-direction: column;
            }
            .premium-lock-overlay .lock-icon {
                font-size: 3rem;
            }
            .premium-lock-overlay h3 {
                font-size: 1.5rem;
            }
            .premium-unlock-btn {
                font-size: 1.1rem;
                padding: 12px 25px;
            }
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
                    <li><a href="probables.php" class="active">Probables</a></li>
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
               Your Shortcut to Exam Success
            </div>
            
            <h1 class="animate-fade-in delay-100">Probables <span class="highlight">Topics</span></h1>
            
            <p class="animate-fade-in delay-200">
                Quick revision materials with key points and images for last-minute preparation.
            </p>
            
            <div class="scroll-indicator">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </section>

    <!-- Probables Section -->
    <section class="probables">
        <div class="container">
            <!-- Search Bar and Filters -->
            <div class="search-filter-bar">
                <input type="text" id="searchInput" placeholder="Search for probables...">
                <select id="yearFilter">
                    <option value="all">Filter by Year</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                    <option value="2022">2022</option>
                    <option value="2021">2021</option>
                    <option value="2020">2020</option>
                </select>
            </div>

            <div id="probablesList">
                <!-- Probables will be loaded here by JavaScript -->
            </div>
        </div>
    </section>

    <!-- PDF Preview Modal -->
    <div id="pdfPreviewModal" class="pdf-modal">
        <div class="pdf-modal-content">
            <div class="pdf-modal-header">
                <h3 id="pdfModalTitle"></h3>
                <span class="pdf-modal-close">&times;</span>
            </div>
            <iframe id="pdfViewerFrame" class="pdf-viewer-frame" frameborder="0"></iframe>
        </div>
    </div>
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
        // Probables page JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            loadProbables();
            
            // Search functionality
            document.getElementById('searchInput').addEventListener('input', function() {
                filterProbables();
            });
            
            // Year filter
            document.getElementById('yearFilter').addEventListener('change', function() {
                filterProbables();
            });
            
            // PDF modal functionality
            const modal = document.getElementById('pdfPreviewModal');
            const closeBtn = document.querySelector('.pdf-modal-close');
            
            closeBtn.onclick = function() {
                modal.style.display = "none";
                document.getElementById('pdfViewerFrame').src = "";
            }
            
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                    document.getElementById('pdfViewerFrame').src = "";
                }
            }
        });

        let allProbables = [];

        function loadProbables() {
            fetch('backend/api.php?action=probables')
                .then(response => response.json())
                .then(data => {
                    allProbables = data;
                    displayProbables(data);
                })
                .catch(error => {
                    console.error('Error loading probables:', error);
                    document.getElementById('probablesList').innerHTML = '<p>Error loading probables. Please try again later.</p>';
                });
        }

        function displayProbables(probables) {
            const container = document.getElementById('probablesList');
            
            if (probables.length === 0) {
                container.innerHTML = '<p>No probables found matching your criteria.</p>';
                return;
            }

            // Group probables by year
            const groupedByYear = probables.reduce((acc, probable) => {
                if (!acc[probable.year]) {
                    acc[probable.year] = [];
                }
                acc[probable.year].push(probable);
                return acc;
            }, {});

            // Sort years in descending order
            const sortedYears = Object.keys(groupedByYear).sort((a, b) => b - a);

            let html = '';
            sortedYears.forEach(year => {
                html += `
                    <div class="year-section">
                        <div class="year-header" onclick="toggleYear('${year}')">
                            <span>${year} Probables</span>
                            <i class="fas fa-chevron-down arrow" id="arrow-${year}"></i>
                        </div>
                        <div class="year-content expanded" id="content-${year}">
                `;
                
                groupedByYear[year].forEach(probable => {
                    html += `
                        <div class="file-item">
                            <div class="file-info">
                                <h4>${probable.title}</h4>
                                <div class="file-meta">
                                    <span><i class="fas fa-calendar"></i> ${probable.year}</span>
                                    <span><i class="fas fa-eye"></i> ${probable.views} views</span>
                                    <span><i class="fas fa-download"></i> ${probable.downloads} downloads</span>
                                </div>
                                <div class="file-tags">
                                    <span class="tag">Year ${probable.year}</span>
                                    ${probable.coming_soon ? '<span class="tag coming-soon-tag">Coming Soon</span>' : ''}
                                </div>
                            </div>
                            <div class="file-actions">
                                <button class="preview-btn" onclick="previewPDF('${probable.file_path}', '${probable.title}', ${probable.id})">
                                    <i class="fas fa-eye"></i> Preview
                                </button>
                                <button class="download-btn" onclick="downloadPDF('${probable.file_path}', '${probable.title}', ${probable.id})">
                                    <i class="fas fa-download"></i> Download
                                </button>
                            </div>
                        </div>
                    `;
                });
                
                html += `
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function toggleYear(year) {
            const content = document.getElementById(`content-${year}`);
            const arrow = document.getElementById(`arrow-${year}`);
            const header = arrow.parentElement;
            
            if (content.classList.contains('expanded')) {
                content.classList.remove('expanded');
                header.classList.add('collapsed');
            } else {
                content.classList.add('expanded');
                header.classList.remove('collapsed');
            }
        }

        function filterProbables() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const yearFilter = document.getElementById('yearFilter').value;
            
            let filtered = allProbables.filter(probable => {
                const matchesSearch = probable.title.toLowerCase().includes(searchTerm);
                const matchesYear = yearFilter === 'all' || probable.year.toString() === yearFilter;
                
                return matchesSearch && matchesYear;
            });
            
            displayProbables(filtered);
        }

        function previewPDF(filePath, title, id) {
            // Update view count
            updateViewCount(id, 'probables');
            
            // Show PDF in modal
            document.getElementById('pdfModalTitle').textContent = title;
            document.getElementById('pdfViewerFrame').src = `uploads/probables/${filePath}`;
            document.getElementById('pdfPreviewModal').style.display = 'flex';
        }

        function downloadPDF(filePath, title, id) {
            // Update download count
            updateDownloadCount(id, 'probables');
            
            // Trigger download
            const link = document.createElement('a');
            link.href = `uploads/probables/${filePath}`;
            link.download = title;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function updateViewCount(id, table) {
            fetch('backend/api.php?action=update_view_count', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${id}&table=${table}`
            });
        }

        function updateDownloadCount(id, table) {
            fetch('backend/api.php?action=update_download_count', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${id}&table=${table}`
            });
        }
    </script>
</body>
</html>

