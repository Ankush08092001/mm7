// Mock Tests JavaScript functionality
const backendUrl = '/backend/api.php';

// Function to load tests
async function loadTests() {
    try {
        const response = await fetch(`${backendUrl}/mock_tests`);
        if (!response.ok) {
            throw new Error('Failed to load tests');
        }
        const tests = await response.json();
        renderTests(tests);
    } catch (error) {
        console.error('Error loading tests:', error);
        showNotification('Failed to load tests. Please try again later.', 'error');
    }
}

// Function to render tests
function renderTests(tests) {
    const testsGrid = document.getElementById('tests-grid');
    if (!testsGrid) return;

    if (tests.length === 0) {
        testsGrid.innerHTML = '<div class="no-tests">No tests found</div>';
        return;
    }

    testsGrid.innerHTML = tests.map(test => `
        <div class="test-card" data-id="${test.id}">
            <div class="test-header">
                <h3>${test.title}</h3>
                <span class="status-badge ${test.status}">${test.status}</span>
            </div>
            <div class="test-info">
                <p><i class="fas fa-book"></i> ${test.type}</p>
                <p><i class="fas fa-calendar"></i> ${new Date(test.created_at).toLocaleDateString()}</p>
            </div>
            <div class="test-actions">
                <button class="action-btn view-btn" onclick="viewTestDetails(${test.id}, '${test.title}')">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="action-btn edit-btn" onclick="editTest(${test.id}, '${test.title}')">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="action-btn delete-btn" onclick="deleteTest(${test.id}, '${test.title}')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    `).join('');
}

// Function to view test details
async function viewTestDetails(testId, testTitle) {
    try {
        const response = await fetch(`${backendUrl}/mock_tests?id=${testId}`);
        if (!response.ok) {
            throw new Error('Failed to load test details');
        }
        const test = await response.json();

        const modal = document.getElementById('test-details-modal');
        if (!modal) return;

        modal.querySelector('#detail-test-title').textContent = test.title;
        modal.querySelector('#detail-test-category').textContent = test.type;
        modal.querySelector('#detail-test-duration').textContent = test.duration || 'N/A';
        modal.querySelector('#detail-test-questions').textContent = test.questions_count || 'N/A';
        modal.querySelector('#detail-test-passing').textContent = test.passing_score || 'N/A';
        modal.querySelector('#detail-test-date').textContent = new Date(test.created_at).toLocaleDateString();
        modal.querySelector('#detail-test-description').textContent = test.description || 'No description available';

        modal.style.display = 'block';
    } catch (error) {
        console.error('Error loading test details:', error);
        showNotification('Failed to load test details. Please try again later.', 'error');
    }
}

// Function to edit test
async function editTest(testId, testTitle) {
    try {
        const response = await fetch(`${backendUrl}/mock_tests?id=${testId}`);
        if (!response.ok) {
            throw new Error('Failed to load test data');
        }
        const test = await response.json();

        const modal = document.getElementById('edit-test-modal');
        if (!modal) return;

        document.getElementById('edit-test-id').value = test.id;
        document.getElementById('edit-test-title').value = test.title;
        document.getElementById('edit-test-type').value = test.type;
        document.getElementById('edit-test-status').value = test.status;
        document.getElementById('edit-test-description').value = test.description || '';

        modal.style.display = 'block';
    } catch (error) {
        console.error('Error loading test data:', error);
        showNotification('Failed to load test data. Please try again later.', 'error');
    }
}

// Function to delete test
async function deleteTest(testId, testTitle) {
    if (!confirm(`Are you sure you want to delete the test "${testTitle}"?`)) {
        return;
    }

    try {
        const response = await fetch(`${backendUrl}/mock_tests?id=${testId}`, {
            method: 'DELETE'
        });

        if (!response.ok) {
            throw new Error('Failed to delete test');
        }

        showNotification('Test deleted successfully', 'success');
        loadTests(); // Reload tests
    } catch (error) {
        console.error('Error deleting test:', error);
        showNotification('Failed to delete test. Please try again later.', 'error');
    }
}

// Function to show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    loadTests();

    // Add test button
    const addTestBtn = document.getElementById('add-test-btn');
    if (addTestBtn) {
        addTestBtn.addEventListener('click', () => {
            const modal = document.getElementById('add-test-modal');
            if (modal) {
                modal.style.display = 'block';
            }
        });
    }

    // Search functionality
    const searchInput = document.getElementById('test-search');
    if (searchInput) {
        searchInput.addEventListener('input', async () => {
            const searchTerm = searchInput.value.toLowerCase();
            try {
                const response = await fetch(`${backendUrl}/mock_tests?search=${searchTerm}`);
                if (!response.ok) {
                    throw new Error('Failed to search tests');
                }
                const tests = await response.json();
                renderTests(tests);
            } catch (error) {
                console.error('Error searching tests:', error);
                showNotification('Failed to search tests. Please try again later.', 'error');
            }
        });
    }

    // Filter functionality
    const typeFilter = document.getElementById('test-type-filter');
    if (typeFilter) {
        typeFilter.addEventListener('change', async () => {
            const type = typeFilter.value;
            try {
                const response = await fetch(`${backendUrl}/mock_tests?type=${type}`);
                if (!response.ok) {
                    throw new Error('Failed to filter tests');
                }
                const tests = await response.json();
                renderTests(tests);
            } catch (error) {
                console.error('Error filtering tests:', error);
                showNotification('Failed to filter tests. Please try again later.', 'error');
            }
        });
    }

    const statusFilter = document.getElementById('test-status-filter');
    if (statusFilter) {
        statusFilter.addEventListener('change', async () => {
            const status = statusFilter.value;
            try {
                const response = await fetch(`${backendUrl}/mock_tests?status=${status}`);
                if (!response.ok) {
                    throw new Error('Failed to filter tests');
                }
                const tests = await response.json();
                renderTests(tests);
            } catch (error) {
                console.error('Error filtering tests:', error);
                showNotification('Failed to filter tests. Please try again later.', 'error');
            }
        });
    }
});
