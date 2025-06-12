<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Materials - MarineMonks</title>
    <meta name="description" content="Access comprehensive MEO Class 4 study materials curated by experts at MarineMonks. Download PDFs and filter by function or author.">
    <link rel="stylesheet" href="css/consolidated.css">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="canonical" href="https://www.marinemonks.in/study-material.php">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <style>
        .function-tag {
            background-color: rgba(0, 184, 148, 0.1);
            color: var(--secondary-color);
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .study-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            overflow: hidden;
            margin-bottom: 30px;
        }
        .study-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .study-card-content {
            padding: 25px;
        }
        .study-card-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark-text);
        }
        .study-card-subtitle {
            color: #555;
            margin-bottom: 8px;
        }
        .study-card-author {
            color: #777;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        .study-card-meta {
            display: flex;
            justify-content: space-between;
            color: #777;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        .study-card-actions {
            display: flex;
            gap: 10px;
        }
        .preview-btn {
            flex: 1;
            padding: 10px;
            text-align: center;
            border: 1px solid var(--secondary-color);
            color: var(--secondary-color);
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: var(--transition);
            text-decoration: none;
            cursor: pointer;
        }
        .preview-btn:hover {
            background-color: rgba(0, 184, 148, 0.1);
        }
        .download-btn {
            flex: 1;
            padding: 10px;
            text-align: center;
            background-color: var(--secondary-color);
            color: white;
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: var(--transition);
            text-decoration: none;
            cursor: pointer;
        }
        .download-btn:hover {
            background-color: #00a382;
        }
        .search-filter {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 25px;
            margin-bottom: 40px;
        }
        .search-filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .search-filter input,
        .search-filter select {
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-family: inherit;
            box-shadow: var(--box-shadow);
            transition: all 0.3s ease;
        }
        .search-filter input:focus,
        .search-filter select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.2);
            outline: none;
        }
        .study-tabs {
            display: flex;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 30px;
            overflow: hidden;
        }
        .study-tab {
            flex: 1;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .study-tab.active {
            background-color: var(--primary-color);
            color: white;
        }
        .study-tab:not(.active) {
            background-color: #f8f9fa;
            color: var(--dark-text);
        }
        .study-tab:not(.active):hover {
            background-color: #e9ecef;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .materials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }
        /* PDF Preview Modal */
        .pdf-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.8);
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
                    <li><a href="study-material.php" class="active">Study Material</a></li>
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
                    Find the Perfect Resources
                </div>
                
                <h1 class="animate-fade-in delay-100">Study <span class="highlight">Materials</span></h1>
                
                <p class="animate-fade-in delay-200">
                    Comprehensive study materials organized by subject and function. Everything you need to ace your MEO Class 4 exams.
                </p>
                
                <div class="scroll-indicator">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </section>

        <!-- Study Materials Section -->
        <section class="study-materials">
            <div class="container">
                <!-- Tabs -->
                <div class="study-tabs animate-on-scroll animated">
                    <div class="study-tab active" onclick="switchTab('written')">
                        <i class="fas fa-book"></i> Written Study Material
                    </div>
                    <div class="study-tab" onclick="switchTab('orals')">
                        <i class="fas fa-microphone"></i> Orals Study Material
                    </div>
                </div>

                <!-- Written Study Materials -->
                <div id="written-content" class="tab-content active">
                    <!-- Search and Filter -->
                    <div class="search-filter animate-on-scroll animated">
                        <div class="search-filter-grid">
                            <input type="text" id="written-search" placeholder="Search materials...">
                            <select id="written-subject">
                                <option value="">All Subjects</option>
                                <option value="MEK-G">MEK-G</option>
                                <option value="MEK-M">MEK-M</option>
                                <option value="MET">MET</option>
                                <option value="MEP">MEP</option>
                                <option value="NAVAL">NAVAL</option>
                                <option value="SSEP">SSEP</option>
                            </select>
                            <select id="written-topic">
                                <option value="">All Topics</option>
                            </select>
                            <select id="written-author">
                                <option value="">All Authors</option>
                                <option value="MarineMonks">MarineMonks</option>
                                <option value="Samraj">Samraj</option>
                                <option value="Dieselship">Dieselship</option>
                                <option value="Ankush Notes">Ankush Notes</option>
                            </select>
                        </div>
                    </div>

                    <!-- Materials Grid -->
                    <div id="written-materials-grid" class="materials-grid">
                        <!-- Materials will be loaded here -->
                    </div>
                </div>

                <!-- Orals Study Materials -->
                <div id="orals-content" class="tab-content">
                    <!-- Search and Filter -->
                    <div class="search-filter animate-on-scroll animated">
                        <div class="search-filter-grid">
                            <input type="text" id="orals-search" placeholder="Search materials...">
                            <select id="orals-function">
                                <option value="">All Functions</option>
                                <option value="Function 3">Function 3 – Operation of ship & care of persons</option>
                                <option value="Function 4B">Function 4B – Marine Engineering at Operational Level</option>
                                <option value="Function 5">Function 5 – Electrical/Electronic/Control</option>
                                <option value="Function 6">Function 6 – Maintenance and Repair</option>
                            </select>
                            <select id="orals-topic">
                                <option value="">All Topics</option>
                                <option value="Emergency Situations">Emergency Situations</option>
                                <option value="System Failures">System Failures</option>
                                <option value="Sketch-based">Sketch-based</option>
                            </select>
                            <select id="orals-author">
                                <option value="">All Authors</option>
                                <option value="MarineMonks">MarineMonks</option>
                                <option value="Samraj">Samraj</option>
                                <option value="Dieselship">Dieselship</option>
                                <option value="Ankush Notes">Ankush Notes</option>
                            </select>
                        </div>
                    </div>

                    <!-- Materials Grid -->
                    <div id="orals-materials" class="materials-grid">
                        <!-- Materials will be loaded here -->
                    </div>
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
        let currentTab = 'written';
        let allMaterials = { written: [], orals: [] };

        document.addEventListener('DOMContentLoaded', function() {
            loadStudyMaterials('written');
            
            // Add event listeners for filters
            setupFilterListeners();
            
            // PDF modal functionality
            setupPDFModal();
        });

        function switchTab(tab) {
            currentTab = tab;
            
            // Update tab appearance
            document.querySelectorAll('.study-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            event.target.classList.add('active');
            document.getElementById(tab + '-content').classList.add('active');
            
            // Load materials for the selected tab
            loadStudyMaterials(tab);
        }

        function loadStudyMaterials(type) {
            const params = new URLSearchParams();
            
            if (type === 'written') {
                const searchTerm = document.getElementById('written-search').value;
                const selectedSubject = document.getElementById('written-subject').value;
                const selectedTopic = document.getElementById('written-topic').value;
                const selectedAuthor = document.getElementById('written-author').value;

                if (searchTerm) params.append('search', searchTerm);
                if (selectedSubject) params.append('subject', selectedSubject);
                if (selectedTopic) params.append('topic', selectedTopic);
                if (selectedAuthor) params.append('author', selectedAuthor);

                fetch(`backend/study_materials.php/written_materials?${params}`)
                    .then(response => response.json())
                    .then(data => {
                        allMaterials[type] = data;
                        displayMaterials(data, type);
                    })
                    .catch(error => {
                        console.error('Error loading written materials:', error);
                        document.getElementById(type + '-materials').innerHTML = '<p>Error loading materials. Please try again later.</p>';
                    });
            } else {
                const searchTerm = document.getElementById('orals-search').value;
                const selectedFunction = document.getElementById('orals-function').value;
                const selectedTopic = document.getElementById('orals-topic').value;
                const selectedAuthor = document.getElementById('orals-author').value;

                if (searchTerm) params.append('search', searchTerm);
                if (selectedFunction) params.append('function', selectedFunction);
                if (selectedTopic) params.append('topic', selectedTopic);
                if (selectedAuthor) params.append('author', selectedAuthor);

                fetch(`backend/study_materials.php/orals_materials?${params}`)
                    .then(response => response.json())
                    .then(data => {
                        allMaterials[type] = data;
                        displayMaterials(data, type);
                    })
                    .catch(error => {
                        console.error('Error loading orals materials:', error);
                        document.getElementById(type + '-materials').innerHTML = '<p>Error loading materials. Please try again later.</p>';
                    });
            }
        }

        function displayMaterials(materials, type) {
            const container = document.getElementById(type + '-materials');
            
            if (materials.length === 0) {
                container.innerHTML = '<p>No materials found matching your criteria.</p>';
                return;
            }

            let html = '';
            materials.forEach(material => {
                if (type === 'written') {
                    html += `
                        <div class="study-card">
                            <div class="study-card-content">
                                <h3 class="study-card-title">${material.title}</h3>
                                <div class="study-card-subtitle">
                                    <span class="function-tag">${material.subject}</span>
                                </div>
                                ${material.topic ? `<div class="study-card-subtitle">Topic: ${material.topic}</div>` : ''}
                                ${material.author ? `<div class="study-card-author">By: ${material.author}</div>` : ''}
                                <div class="study-card-meta">
                                    <span>${material.pages || 'N/A'} pages</span>
                                    <span><i class="fas fa-download"></i> ${material.downloads} downloads</span>
                                </div>
                                <div class="study-card-actions">
                                    <button class="preview-btn" onclick="previewPDF('${material.file_path}', '${material.title}', ${material.id})">
                                        <i class="fas fa-eye"></i> Preview
                                    </button>
                                    <button class="download-btn" onclick="downloadPDF('${material.file_path}', '${material.title}', ${material.id})">
                                        <i class="fas fa-download"></i> Download
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    html += `
                        <div class="study-card">
                            <div class="study-card-content">
                                <h3 class="study-card-title">${material.question_type}</h3>
                                <div class="study-card-subtitle">
                                    <span class="function-tag">${material.function}</span>
                                </div>
                                ${material.topic ? `<div class="study-card-subtitle">Topic: ${material.topic}</div>` : ''}
                                ${material.author ? `<div class="study-card-author">By: ${material.author}</div>` : ''}
                                <div class="study-card-meta">
                                    <span>Uploaded: ${new Date(material.upload_date).toLocaleDateString()}</span>
                                    <span><i class="fas fa-download"></i> ${material.downloads} downloads</span>
                                </div>
                                <div class="study-card-actions">
                                    <button class="preview-btn" onclick="previewPDF('${material.file_path}', '${material.question_type}', ${material.id})">
                                        <i class="fas fa-eye"></i> Preview
                                    </button>
                                    <button class="download-btn" onclick="downloadPDF('${material.file_path}', '${material.question_type}', ${material.id})">
                                        <i class="fas fa-download"></i> Download
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                }
            });

            container.innerHTML = html;
        }

        function setupFilterListeners() {
            // Written materials filters
            ['written-search', 'written-subject', 'written-topic', 'written-author'].forEach(id => {
                document.getElementById(id).addEventListener('input', () => loadStudyMaterials('written'));
                document.getElementById(id).addEventListener('change', () => loadStudyMaterials('written'));
            });

            // Orals materials filters
            ['orals-search', 'orals-function', 'orals-topic', 'orals-author'].forEach(id => {
                document.getElementById(id).addEventListener('input', () => loadStudyMaterials('orals'));
                document.getElementById(id).addEventListener('change', () => loadStudyMaterials('orals'));
            });
        }

        function setupPDFModal() {
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
        }

        function previewPDF(filePath, title, id) {
            // Show PDF in modal
            document.getElementById('pdfModalTitle').textContent = title;
            document.getElementById('pdfViewerFrame').src = `backend/study_materials.php/download/${filePath.split('/').pop()}`;
            document.getElementById('pdfPreviewModal').style.display = 'flex';
        }

        function downloadPDF(filePath, title, id) {
            // Trigger download
            const link = document.createElement('a');
            link.href = `backend/study_materials.php/download/${filePath.split('/').pop()}`;
            link.download = title;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>
</html>

