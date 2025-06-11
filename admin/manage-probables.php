<?php
require_once '../config/db.php';
require_once '../config/auth.php';

// Check if user is admin
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../login.php');
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getDBConnection();
    
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
                    $title = $_POST['title'];
                    $year = (int)$_POST['year'];
                    $coming_soon = isset($_POST['coming_soon']) ? 1 : 0;
                    
                    // Generate unique filename
                    $filename = uniqid() . '_' . basename($_FILES['pdf_file']['name']);
                    $upload_path = '../uploads/probables/' . $filename;
                    
                    if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $upload_path)) {
                        $stmt = $conn->prepare("INSERT INTO probables (title, year, file_path, coming_soon) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("sisi", $title, $year, $filename, $coming_soon);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
                break;
                
            case 'edit':
                $id = (int)$_POST['id'];
                $title = $_POST['title'];
                $year = (int)$_POST['year'];
                $coming_soon = isset($_POST['coming_soon']) ? 1 : 0;
                
                if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
                    // Delete old file
                    $stmt = $conn->prepare("SELECT file_path FROM probables WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($row = $result->fetch_assoc()) {
                        $old_file = '../uploads/probables/' . $row['file_path'];
                        if (file_exists($old_file)) {
                            unlink($old_file);
                        }
                    }
                    
                    // Upload new file
                    $filename = uniqid() . '_' . basename($_FILES['pdf_file']['name']);
                    $upload_path = '../uploads/probables/' . $filename;
                    
                    if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $upload_path)) {
                        $stmt = $conn->prepare("UPDATE probables SET title = ?, year = ?, file_path = ?, coming_soon = ? WHERE id = ?");
                        $stmt->bind_param("sissi", $title, $year, $filename, $coming_soon, $id);
                    }
                } else {
                    $stmt = $conn->prepare("UPDATE probables SET title = ?, year = ?, coming_soon = ? WHERE id = ?");
                    $stmt->bind_param("siii", $title, $year, $coming_soon, $id);
                }
                $stmt->execute();
                $stmt->close();
                break;
                
            case 'delete':
                $id = (int)$_POST['id'];
                
                // Delete file
                $stmt = $conn->prepare("SELECT file_path FROM probables WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($row = $result->fetch_assoc()) {
                    $file = '../uploads/probables/' . $row['file_path'];
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
                
                // Delete from database
                $stmt = $conn->prepare("DELETE FROM probables WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt->close();
                break;
        }
    }
    
    $conn->close();
    header('Location: manage-probables.php');
    exit();
}

// Get all probables
$conn = getDBConnection();
$probables = [];

if ($conn) {
    $query = "SELECT * FROM probables ORDER BY year DESC, title ASC";
    $result = $conn->query($query);
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $probables[] = $row;
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Probables - Admin Dashboard</title>
    <link rel="stylesheet" href="../css/consolidated.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <style>
        .admin-container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            background: var(--gradient-primary);
            padding: 2rem;
            border-radius: var(--border-radius);
            color: white;
        }
        
        .admin-title {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }
        
        .probable-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .probable-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
            transition: var(--transition);
        }
        
        .probable-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .probable-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        
        .probable-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-text);
            margin: 0;
        }
        
        .probable-stats {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            color: #666;
            font-size: 0.9rem;
        }
        
        .probable-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .badge-success {
            background: var(--success-light);
            color: var(--success);
        }
        
        .badge-warning {
            background: var(--warning-light);
            color: var(--warning);
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            backdrop-filter: blur(5px);
        }
        
        .modal-content {
            position: relative;
            background: white;
            width: 90%;
            max-width: 500px;
            margin: 2rem auto;
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            animation: modalSlideIn 0.3s ease-out;
        }
        
        @keyframes modalSlideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            transition: var(--transition);
        }
        
        .modal-close:hover {
            color: var(--primary-color);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark-text);
        }
        
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }
        
        .form-group input[type="text"]:focus,
        .form-group input[type="number"]:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px var(--primary-light);
        }
        
        .form-group input[type="checkbox"] {
            margin-right: 0.5rem;
        }
        
        .checkbox-label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: var(--transition);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background: var(--primary-color);
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-outline {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }
        
        .btn-outline:hover {
            background: var(--primary-light);
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background: var(--danger);
            color: white;
            border: none;
        }
        
        .btn-danger:hover {
            background: var(--danger-dark);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav class="navbar">
                <a href="../index.html" class="logo" aria-label="MarineMonks Home">
                    <img src="../images/logo/logo-new.webp" alt="MarineMonks Logo" width="50" height="50">
                    <span class="logo-text">MarineMonks</span>
                </a>
                
                <div class="mobile-menu-toggle" aria-expanded="false" aria-label="Toggle navigation menu">
                    <i class="fas fa-bars"></i>
                </div>
                
                <ul class="nav-links">
                    <li><a href="../index.html">Home</a></li>
                    <li><a href="../study-material.html">Study Material</a></li>
                    <li><a href="../mock-tests.html">Mock Tests</a></li>
                    <li><a href="../papers.html">Papers</a></li>
                    <li><a href="../probables.php">Probables</a></li>
                </ul>
                
                <div class="auth-buttons">
                    <a href="../user/dashboard.php" class="btn btn-outline">Dashboard</a>
                    <a href="../logout.php" class="btn btn-primary">Logout</a>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <div class="admin-container">
            <div class="admin-header">
                <h1 class="admin-title">Manage Probables</h1>
                <button class="btn btn-primary" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Add New Probable
                </button>
            </div>
            
            <div class="probable-grid">
                <?php foreach ($probables as $probable): ?>
                    <div class="probable-card">
                        <div class="probable-header">
                            <h3 class="probable-title"><?php echo htmlspecialchars($probable['title']); ?></h3>
                            <?php if ($probable['coming_soon']): ?>
                                <span class="badge badge-warning">Coming Soon</span>
                            <?php else: ?>
                                <span class="badge badge-success">Available</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="probable-stats">
                            <span><i class="fas fa-eye"></i> <?php echo $probable['views']; ?> views</span>
                            <span><i class="fas fa-download"></i> <?php echo $probable['downloads']; ?> downloads</span>
                        </div>
                        
                        <div class="probable-actions">
                            <button class="btn btn-outline" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($probable)); ?>)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger" onclick="confirmDelete(<?php echo $probable['id']; ?>)">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <!-- Add Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeAddModal()">&times;</span>
            <h2>Add New Probable</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label for="year">Year</label>
                    <input type="number" id="year" name="year" required min="2000" max="2099">
                </div>
                
                <div class="form-group">
                    <label for="pdf_file">PDF File</label>
                    <input type="file" id="pdf_file" name="pdf_file" accept=".pdf" required>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="coming_soon">
                        Mark as Coming Soon
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Probable
                </button>
            </form>
        </div>
    </div>
    
    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Probable</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="form-group">
                    <label for="edit_title">Title</label>
                    <input type="text" id="edit_title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_year">Year</label>
                    <input type="number" id="edit_year" name="year" required min="2000" max="2099">
                </div>
                
                <div class="form-group">
                    <label for="edit_pdf_file">PDF File (leave empty to keep current file)</label>
                    <input type="file" id="edit_pdf_file" name="pdf_file" accept=".pdf">
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="coming_soon" id="edit_coming_soon">
                        Mark as Coming Soon
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Probable
                </button>
            </form>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h2>Confirm Delete</h2>
            <p>Are you sure you want to delete this probable? This action cannot be undone.</p>
            <form action="" method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="delete_id">
                <div class="action-buttons">
                    <button type="button" class="btn btn-outline" onclick="closeDeleteModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <a href="../index.html" class="footer-logo">
                        <img src="../images/logo/logo-new.webp" alt="MarineMonks Logo" width="40" height="40">
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
                        <li><a href="../index.html">Home</a></li>
                        <li><a href="../study-material.html">Study Material</a></li>
                        <li><a href="../mock-tests.html">Mock Tests</a></li>
                        <li><a href="../papers.html">Papers</a></li>
                        <li><a href="../probables.php">Probables</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="../contact.html">Contact Us</a></li>
                        <li><a href="../faq.html">FAQ</a></li>
                        <li><a href="../help.html">Help Center</a></li>
                        <li><a href="../feedback.html">Feedback</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="../privacy.html">Privacy Policy</a></li>
                        <li><a href="../terms.html">Terms of Service</a></li>
                        <li><a href="../refund.html">Refund Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 MarineMonks. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="../js/navigation.js"></script>
    <script>
        // Modal functions
        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }
        
        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }
        
        function openEditModal(probable) {
            document.getElementById('edit_id').value = probable.id;
            document.getElementById('edit_title').value = probable.title;
            document.getElementById('edit_year').value = probable.year;
            document.getElementById('edit_coming_soon').checked = probable.coming_soon === "1";
            document.getElementById('editModal').style.display = 'block';
        }
        
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        function confirmDelete(id) {
            document.getElementById('delete_id').value = id;
            document.getElementById('deleteModal').style.display = 'block';
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
        
        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html> 