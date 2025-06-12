<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Include database configuration
require_once '../config/database.php';

// Get test ID from URL
$test_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get test data
$stmt = $pdo->prepare("SELECT * FROM mock_tests WHERE id = ?");
$stmt->execute([$test_id]);
$test = $stmt->fetch();

if (!$test) {
    header('Location: tests.php');
    exit();
}

// Handle question actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'delete':
                if (isset($_POST['question_id'])) {
                    $stmt = $pdo->prepare("DELETE FROM test_questions WHERE id = ? AND test_id = ?");
                    $stmt->execute([$_POST['question_id'], $test_id]);
                }
                break;
            
            case 'reorder':
                if (isset($_POST['questions'])) {
                    $questions = json_decode($_POST['questions'], true);
                    foreach ($questions as $order => $question_id) {
                        $stmt = $pdo->prepare("UPDATE test_questions SET question_order = ? WHERE id = ? AND test_id = ?");
                        $stmt->execute([$order + 1, $question_id, $test_id]);
                    }
                }
                break;
        }
    }
}

// Get questions for the test
$stmt = $pdo->prepare("
    SELECT tq.*, q.question_text, q.question_type, q.options, q.correct_answer
    FROM test_questions tq
    JOIN questions q ON tq.question_id = q.id
    WHERE tq.test_id = ?
    ORDER BY tq.question_order
");
$stmt->execute([$test_id]);
$questions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Questions - Admin Portal</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <img src="../assets/images/logo.png" alt="Logo">
                <span>Admin Portal</span>
            </div>
            <nav>
                <ul class="admin-nav">
                    <li class="admin-nav-item">
                        <a href="index.php" class="admin-nav-link">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="users.php" class="admin-nav-link">
                            <i class="fas fa-users"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="tests.php" class="admin-nav-link active">
                            <i class="fas fa-file-alt"></i>
                            <span>Mock Tests</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="questions.php" class="admin-nav-link">
                            <i class="fas fa-question-circle"></i>
                            <span>Questions</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="results.php" class="admin-nav-link">
                            <i class="fas fa-chart-bar"></i>
                            <span>Results</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="settings.php" class="admin-nav-link">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-content">
            <header class="admin-header">
                <h1 class="admin-header-title">Test Questions: <?php echo htmlspecialchars($test['title']); ?></h1>
                <div class="admin-header-actions">
                    <a href="add_question.php?test_id=<?php echo $test_id; ?>" class="admin-btn admin-btn-primary">
                        <i class="fas fa-plus"></i> Add Question
                    </a>
                    <a href="tests.php" class="admin-btn admin-btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Tests
                    </a>
                </div>
            </header>

            <!-- Questions List -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Questions (<?php echo count($questions); ?>)</h2>
                    <small class="text-muted">Drag and drop to reorder questions</small>
                </div>

                <?php if (empty($questions)): ?>
                <div class="alert alert-info">
                    No questions added yet. Click "Add Question" to start adding questions to this test.
                </div>
                <?php else: ?>
                <div class="questions-list" id="sortable-questions">
                    <?php foreach ($questions as $question): ?>
                    <div class="question-item" data-id="<?php echo $question['id']; ?>">
                        <div class="question-header">
                            <div class="question-order">
                                <i class="fas fa-grip-vertical"></i>
                                <span>Question <?php echo $question['question_order']; ?></span>
                            </div>
                            <div class="question-actions">
                                <a href="edit_question.php?id=<?php echo $question['id']; ?>&test_id=<?php echo $test_id; ?>" 
                                   class="admin-btn admin-btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" style="display: inline;" 
                                      onsubmit="return confirm('Are you sure you want to remove this question from the test?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">
                                    <button type="submit" class="admin-btn admin-btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="question-content">
                            <p class="question-text"><?php echo htmlspecialchars($question['question_text']); ?></p>
                            <div class="question-meta">
                                <span class="badge badge-info"><?php echo ucfirst($question['question_type']); ?></span>
                                <?php if ($question['question_type'] === 'multiple_choice'): ?>
                                <span class="badge badge-secondary">
                                    <?php 
                                    $options = json_decode($question['options'], true);
                                    echo count($options) . ' options';
                                    ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script>
        // Initialize drag and drop sorting
        new Sortable(document.getElementById('sortable-questions'), {
            animation: 150,
            handle: '.fa-grip-vertical',
            onEnd: function(evt) {
                // Get new order of questions
                const questions = Array.from(evt.to.children).map(item => item.dataset.id);
                
                // Update order in database
                fetch('test_questions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=reorder&questions=${JSON.stringify(questions)}`
                }).then(response => {
                    if (response.ok) {
                        // Update question numbers
                        document.querySelectorAll('.question-order span').forEach((span, index) => {
                            span.textContent = `Question ${index + 1}`;
                        });
                    }
                });
            }
        });
    </script>
</body>
</html> 