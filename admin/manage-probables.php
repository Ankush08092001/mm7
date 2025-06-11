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
        }
        
        .admin-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-text);
        }
        
        .probable-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        .probable-table th,
        .probable-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .probable-table th {
            background: #f8f9fa;
            font-weight: 600;
        }
        
        .probable-table tr:last-child td {
            border-bottom: none;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
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
        }
        
        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
        }
        
        .form-group input[type="checkbox"] {
            margin-right: 0.5rem;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .badge-success {
            background: #dcfce7;
            color: #166534;
        }
        
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">Manage Probables</h1>
            <button class="btn btn-primary" onclick="openAddModal()">
                <i class="fas fa-plus"></i> Add New Probable
            </button>
        </div>
        
        <table class="probable-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Year</th>
                    <th>Views</th>
                    <th>Downloads</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($probables as $probable): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($probable['title']); ?></td>
                        <td><?php echo $probable['year']; ?></td>
                        <td><?php echo $probable['views']; ?></td>
                        <td><?php echo $probable['downloads']; ?></td>
                        <td>
                            <?php if ($probable['coming_soon']): ?>
                                <span class="badge badge-warning">Coming Soon</span>
                            <?php else: ?>
                                <span class="badge badge-success">Available</span>
                            <?php endif; ?>
                        </td>
                        <td class="action-buttons">
                            <button class="btn btn-outline" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($probable)); ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline" onclick="confirmDelete(<?php echo $probable['id']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
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
                    <label>
                        <input type="checkbox" name="coming_soon">
                        Mark as Coming Soon
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary">Add Probable</button>
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
                    <label>
                        <input type="checkbox" name="coming_soon" id="edit_coming_soon">
                        Mark as Coming Soon
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Probable</button>
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
                    <button type="button" class="btn btn-outline" onclick="closeDeleteModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Delete</button>
                </div>
            </form>
        </div>
    </div>
    
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