<?php
$current_page = 'dashboard';
require_once 'includes/header.php';

// Get user progress data
$progress = getProgressData($user_id);
$topic_progress = getTopicProgress($user_id);

// Get recent activity
$recent_tests = getRecentTests(5);
$recent_bookmarks = getRecentBookmarks(5);
?>

<div class="dashboard-content">
    <!-- Welcome Section -->
    <div class="dashboard-card welcome-section">
        <div class="card-header">
            <h3>Welcome back, <?php echo htmlspecialchars($user_data['name']); ?>!</h3>
            <span class="membership-badge <?php echo $user_data['membership_tier']; ?>">
                <?php echo ucfirst($user_data['membership_tier']); ?> Member
            </span>
        </div>
        <div class="card-content">
            <p class="last-login">
                <i class="fas fa-clock"></i>
                Last login: <?php echo date('F j, Y g:i A', strtotime($user_data['last_login'])); ?>
            </p>
        </div>
    </div>

    <!-- Progress Overview -->
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fas fa-chart-bar"></i> Tests Taken</h3>
            </div>
            <div class="card-content">
                <div class="progress-value"><?php echo $progress['total_tests']; ?></div>
                <div class="progress-label">Total Tests</div>
                <div class="progress-bar">
                    <div class="progress" style="width: <?php echo min(($progress['total_tests'] / 50) * 100, 100); ?>%"></div>
                </div>
            </div>
        </div>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fas fa-star"></i> Average Score</h3>
            </div>
            <div class="card-content">
                <div class="progress-value"><?php echo number_format($progress['average_score'], 1); ?>%</div>
                <div class="progress-label">Overall Performance</div>
                <div class="progress-bar">
                    <div class="progress" style="width: <?php echo $progress['average_score']; ?>%"></div>
                </div>
            </div>
        </div>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fas fa-bookmark"></i> Bookmarks</h3>
            </div>
            <div class="card-content">
                <div class="progress-value"><?php echo $progress['total_bookmarks']; ?></div>
                <div class="progress-label">Saved Items</div>
                <div class="progress-bar">
                    <div class="progress" style="width: <?php echo min(($progress['total_bookmarks'] / 20) * 100, 100); ?>%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Topic Progress -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3><i class="fas fa-graduation-cap"></i> Progress by Topic</h3>
        </div>
        <div class="card-content">
            <div class="topic-grid">
                <?php foreach ($topic_progress as $topic): ?>
                <div class="topic-card" data-tooltip="Click to view detailed progress">
                    <h4><?php echo htmlspecialchars($topic['topic_name']); ?></h4>
                    <div class="topic-stats">
                        <div class="stat">
                            <span class="stat-value"><?php echo $topic['tests_attempted']; ?></span>
                            <span class="stat-label">Tests</span>
                        </div>
                        <div class="stat">
                            <span class="stat-value"><?php echo number_format($topic['average_score'], 1); ?>%</span>
                            <span class="stat-label">Score</span>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress" style="width: <?php echo $topic['average_score']; ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fas fa-history"></i> Recent Tests</h3>
                <a href="mock-tests.php" class="view-all">View All</a>
            </div>
            <div class="card-content">
                <?php if (empty($recent_tests)): ?>
                    <p class="no-data">No tests taken yet.</p>
                <?php else: ?>
                    <div class="activity-list">
                        <?php foreach ($recent_tests as $test): ?>
                        <div class="activity-item" data-tooltip="Click to view test details">
                            <div class="activity-icon test-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="activity-details">
                                <h4><?php echo htmlspecialchars($test['test_name']); ?></h4>
                                <p>
                                    <span class="score <?php echo $test['score'] >= 70 ? 'text-success' : ($test['score'] >= 50 ? 'text-warning' : 'text-danger'); ?>">
                                        Score: <?php echo $test['score']; ?>%
                                    </span>
                                    <?php if ($test['feedback']): ?>
                                        <span class="feedback">
                                            <i class="fas fa-comment"></i> Evaluated
                                        </span>
                                    <?php endif; ?>
                                </p>
                                <span class="activity-time">
                                    <i class="fas fa-calendar"></i>
                                    <?php echo date('M d, Y', strtotime($test['submitted_at'])); ?>
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fas fa-bookmark"></i> Recent Bookmarks</h3>
                <a href="bookmarks.php" class="view-all">View All</a>
            </div>
            <div class="card-content">
                <?php if (empty($recent_bookmarks)): ?>
                    <p class="no-data">No bookmarks yet.</p>
                <?php else: ?>
                    <div class="activity-list">
                        <?php foreach ($recent_bookmarks as $bookmark): ?>
                        <div class="activity-item" data-tooltip="Click to view bookmarked item">
                            <div class="activity-icon bookmark-icon">
                                <i class="fas fa-bookmark"></i>
                            </div>
                            <div class="activity-details">
                                <h4><?php echo htmlspecialchars($bookmark['title']); ?></h4>
                                <p>
                                    <span class="resource-type">
                                        <i class="fas fa-tag"></i>
                                        <?php echo ucfirst(str_replace('_', ' ', $bookmark['resource_type'])); ?>
                                    </span>
                                </p>
                                <span class="activity-time">
                                    <i class="fas fa-calendar"></i>
                                    <?php echo date('M d, Y', strtotime($bookmark['created_at'])); ?>
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-content {
    padding: 2rem;
}

.welcome-section {
    margin-bottom: 2rem;
}

.welcome-section h1 {
    font-size: 2rem;
    color: #333;
    margin-bottom: 0.5rem;
}

.last-login {
    color: #666;
    font-size: 0.9rem;
}

.progress-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.progress-card {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.progress-card h3 {
    color: #666;
    font-size: 1rem;
    margin-bottom: 1rem;
}

.progress-value {
    font-size: 2rem;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.progress-label {
    color: #666;
    font-size: 0.9rem;
}

.topic-progress {
    margin-bottom: 2rem;
}

.topic-progress h2 {
    margin-bottom: 1rem;
}

.topic-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.topic-card {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.topic-card h4 {
    color: #2c3e50;
    margin-bottom: 1rem;
}

.topic-stats {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 1rem;
}

.stat {
    text-align: center;
}

.stat-value {
    display: block;
    font-size: 1.5rem;
    font-weight: bold;
    color: #2c3e50;
}

.stat-label {
    font-size: 0.9rem;
    color: #666;
}

.progress-bar {
    height: 8px;
    background: #eee;
    border-radius: 4px;
    overflow: hidden;
}

.progress {
    height: 100%;
    background: #3498db;
    transition: width 0.3s ease;
}

.recent-activity {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.activity-section h2 {
    margin-bottom: 1rem;
}

.activity-list {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #eee;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 1rem;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}

.test-icon {
    background: #e3f2fd;
    color: #1976d2;
}

.bookmark-icon {
    background: #fff3e0;
    color: #f57c00;
}

.activity-details {
    flex: 1;
}

.activity-details h4 {
    margin: 0 0 0.25rem;
    color: #2c3e50;
}

.activity-details p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}

.activity-time {
    font-size: 0.8rem;
    color: #999;
}

.no-data {
    text-align: center;
    padding: 2rem;
    color: #666;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .dashboard-content {
        padding: 1rem;
    }

    .progress-overview {
        grid-template-columns: 1fr;
    }

    .topic-grid {
        grid-template-columns: 1fr;
    }

    .recent-activity {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?> 