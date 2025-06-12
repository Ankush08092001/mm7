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
$test_id = isset($_GET['test_id']) ? (int)$_GET['test_id'] : 0;

// Get test data
$stmt = $pdo->prepare("SELECT * FROM mock_tests WHERE id = ?");
$stmt->execute([$test_id]);
$test = $stmt->fetch();

if (!$test) {
    header('Location: tests.php');
    exit();
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_text = trim($_POST['question_text'] ?? '');
    $question_type = $_POST['question_type'] ?? '';
    $options = [];
    $correct_answer = '';

    // Validate required fields
    if (empty($question_text)) {
        $error = 'Question text is required';
    } elseif (empty($question_type)) {
        $error = 'Question type is required';
    } else {
        // Handle different question types
        switch ($question_type) {
            case 'multiple_choice':
                $options = array_filter($_POST['options'] ?? [], 'trim');
                $correct_answer = $_POST['correct_answer'] ?? '';
                
                if (count($options) < 2) {
                    $error = 'At least 2 options are required for multiple choice questions';
                } elseif (empty($correct_answer)) {
                    $error = 'Please select a correct answer';
                }
                break;

            case 'true_false':
                $correct_answer = $_POST['correct_answer'] ?? '';
                if (empty($correct_answer)) {
                    $error = 'Please select the correct answer';
                }
                break;

            case 'short_answer':
                $correct_answer = trim($_POST['correct_answer'] ?? '');
                if (empty($correct_answer)) {
                    $error = 'Correct answer is required';
                }
                break;
        }
    }

    if (empty($error)) {
        try {
            $pdo->beginTransaction();

            // Insert question
            $stmt = $pdo->prepare("
                INSERT INTO questions (question_text, question_type, options, correct_answer)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $question_text,
                $question_type,
                json_encode($options),
                $correct_answer
            ]);
            $question_id = $pdo->lastInsertId();

            // Get max question order
            $stmt = $pdo->prepare("
                SELECT MAX(question_order) as max_order 
                FROM test_questions 
                WHERE test_id = ?
            ");
            $stmt->execute([$test_id]);
            $max_order = $stmt->fetch()['max_order'] ?? 0;

            // Add question to test
            $stmt = $pdo->prepare("
                INSERT INTO test_questions (test_id, question_id, question_order)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$test_id, $question_id, $max_order + 1]);

            $pdo->commit();
            $success = 'Question added successfully';
            
            // Redirect to test questions page
            header("Location: test_questions.php?id=$test_id");
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'An error occurred while adding the question';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question - Admin Portal</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                <h1 class="admin-header-title">Add Question to: <?php echo htmlspecialchars($test['title']); ?></h1>
                <div class="admin-header-actions">
                    <a href="test_questions.php?id=<?php echo $test_id; ?>" class="admin-btn admin-btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Questions
                    </a>
                </div>
            </header>

            <?php if ($error): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
            <?php endif; ?>

            <!-- Add Question Form -->
            <div class="admin-card">
                <form method="POST" class="admin-form">
                    <div class="form-group">
                        <label for="question_text">Question Text</label>
                        <textarea name="question_text" id="question_text" rows="3" required
                                  class="form-control"><?php echo htmlspecialchars($_POST['question_text'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="question_type">Question Type</label>
                        <select name="question_type" id="question_type" required class="form-control">
                            <option value="">Select Type</option>
                            <option value="multiple_choice" <?php echo ($_POST['question_type'] ?? '') === 'multiple_choice' ? 'selected' : ''; ?>>
                                Multiple Choice
                            </option>
                            <option value="true_false" <?php echo ($_POST['question_type'] ?? '') === 'true_false' ? 'selected' : ''; ?>>
                                True/False
                            </option>
                            <option value="short_answer" <?php echo ($_POST['question_type'] ?? '') === 'short_answer' ? 'selected' : ''; ?>>
                                Short Answer
                            </option>
                        </select>
                    </div>

                    <!-- Multiple Choice Options -->
                    <div id="multiple_choice_options" style="display: none;">
                        <div class="form-group">
                            <label>Options</label>
                            <div id="options_container">
                                <?php
                                $options = $_POST['options'] ?? ['', ''];
                                foreach ($options as $index => $option):
                                ?>
                                <div class="option-row">
                                    <input type="text" name="options[]" value="<?php echo htmlspecialchars($option); ?>"
                                           class="form-control" placeholder="Option <?php echo $index + 1; ?>">
                                    <?php if ($index >= 2): ?>
                                    <button type="button" class="admin-btn admin-btn-danger remove-option">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" id="add_option" class="admin-btn admin-btn-secondary">
                                <i class="fas fa-plus"></i> Add Option
                            </button>
                        </div>

                        <div class="form-group">
                            <label>Correct Answer</label>
                            <select name="correct_answer" class="form-control">
                                <option value="">Select Correct Answer</option>
                                <?php foreach ($options as $index => $option): ?>
                                <option value="<?php echo $index; ?>" 
                                        <?php echo ($_POST['correct_answer'] ?? '') == $index ? 'selected' : ''; ?>>
                                    Option <?php echo $index + 1; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- True/False Options -->
                    <div id="true_false_options" style="display: none;">
                        <div class="form-group">
                            <label>Correct Answer</label>
                            <div class="radio-group">
                                <label>
                                    <input type="radio" name="correct_answer" value="true"
                                           <?php echo ($_POST['correct_answer'] ?? '') === 'true' ? 'checked' : ''; ?>>
                                    True
                                </label>
                                <label>
                                    <input type="radio" name="correct_answer" value="false"
                                           <?php echo ($_POST['correct_answer'] ?? '') === 'false' ? 'checked' : ''; ?>>
                                    False
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Short Answer Options -->
                    <div id="short_answer_options" style="display: none;">
                        <div class="form-group">
                            <label for="correct_answer">Correct Answer</label>
                            <input type="text" name="correct_answer" id="correct_answer"
                                   value="<?php echo htmlspecialchars($_POST['correct_answer'] ?? ''); ?>"
                                   class="form-control">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="fas fa-save"></i> Save Question
                        </button>
                        <a href="test_questions.php?id=<?php echo $test_id; ?>" class="admin-btn admin-btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script>
        // Show/hide options based on question type
        const questionType = document.getElementById('question_type');
        const multipleChoiceOptions = document.getElementById('multiple_choice_options');
        const trueFalseOptions = document.getElementById('true_false_options');
        const shortAnswerOptions = document.getElementById('short_answer_options');

        function updateOptionsDisplay() {
            multipleChoiceOptions.style.display = 'none';
            trueFalseOptions.style.display = 'none';
            shortAnswerOptions.style.display = 'none';

            switch (questionType.value) {
                case 'multiple_choice':
                    multipleChoiceOptions.style.display = 'block';
                    break;
                case 'true_false':
                    trueFalseOptions.style.display = 'block';
                    break;
                case 'short_answer':
                    shortAnswerOptions.style.display = 'block';
                    break;
            }
        }

        questionType.addEventListener('change', updateOptionsDisplay);
        updateOptionsDisplay();

        // Handle multiple choice options
        const optionsContainer = document.getElementById('options_container');
        const addOptionBtn = document.getElementById('add_option');

        addOptionBtn.addEventListener('click', () => {
            const optionCount = optionsContainer.children.length;
            const optionRow = document.createElement('div');
            optionRow.className = 'option-row';
            optionRow.innerHTML = `
                <input type="text" name="options[]" class="form-control" placeholder="Option ${optionCount + 1}">
                <button type="button" class="admin-btn admin-btn-danger remove-option">
                    <i class="fas fa-times"></i>
                </button>
            `;
            optionsContainer.appendChild(optionRow);

            // Update correct answer options
            const correctAnswerSelect = document.querySelector('select[name="correct_answer"]');
            const option = document.createElement('option');
            option.value = optionCount;
            option.textContent = `Option ${optionCount + 1}`;
            correctAnswerSelect.appendChild(option);
        });

        optionsContainer.addEventListener('click', (e) => {
            if (e.target.closest('.remove-option')) {
                const optionRow = e.target.closest('.option-row');
                const index = Array.from(optionsContainer.children).indexOf(optionRow);
                optionRow.remove();

                // Update correct answer options
                const correctAnswerSelect = document.querySelector('select[name="correct_answer"]');
                correctAnswerSelect.remove(index + 1); // +1 because of the default option

                // Update remaining option numbers
                optionsContainer.querySelectorAll('input[name="options[]"]').forEach((input, i) => {
                    input.placeholder = `Option ${i + 1}`;
                });
                correctAnswerSelect.querySelectorAll('option:not(:first-child)').forEach((option, i) => {
                    option.textContent = `Option ${i + 1}`;
                    option.value = i;
                });
            }
        });
    </script>
</body>
</html> 