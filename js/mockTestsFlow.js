// Mock Tests Flow JavaScript - Handles tab switching and test card rendering
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const testTabs = document.querySelectorAll('.test-tab');
    const testCardsContainer = document.querySelector('.grid');
    
    // Initialize with written tests active
    let currentTab = 'written';
    
    // Add click event listeners to tabs
    testTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            testTabs.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Determine which tab was clicked
            if (this.textContent.includes('Written Tests')) {
                currentTab = 'written';
            } else if (this.textContent.includes('Oral Tests')) {
                currentTab = 'oral';
            }
            
            // Re-render test cards for the selected tab
            renderTestCards(currentTab);
        });
    });
    
    // Function to render test cards based on type
    function renderTestCards(type) {
        if (!testCardsContainer) return;
        
        // Filter tests based on type
        const filteredTests = mockTestsData.filter(test => test.type === type);
        
        // Clear existing cards
        testCardsContainer.innerHTML = '';
        
        // Generate HTML for each test
        filteredTests.forEach(test => {
            const testCard = createTestCard(test);
            testCardsContainer.appendChild(testCard);
        });
        
        // Add animation class to new cards
        const newCards = testCardsContainer.querySelectorAll('.test-card');
        newCards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.add('animate-on-scroll');
            }, index * 100);
        });
    }
    
    // Function to create a test card element
    function createTestCard(test) {
        const cardDiv = document.createElement('div');
        cardDiv.className = 'test-card';
        
        // Generate star rating HTML
        const starsHtml = Array(5).fill().map((_, i) => 
            `<i class="fas fa-star"></i>`
        ).join('');
        
        // Determine badge class
        const badgeClass = test.badge === 'FREE' ? 'free-badge' : 'premium-badge';
        
        // Create coming soon indicator if applicable
        const comingSoonHtml = test.comingSoon ? 
            `<span class="coming-soon-indicator">Coming Soon</span>` : '';
        
        // Create button HTML - always show the button, but disable if coming soon
        const buttonHtml = test.comingSoon ? 
            `<button class="start-test-btn" disabled style="opacity: 0.6; cursor: not-allowed;">
                Coming Soon
            </button>` :
            `<a href="${test.link}" class="start-test-btn">Start Test</a>`;
        
        // Create locked overlay for coming soon tests
        const lockedOverlayHtml = test.comingSoon ? 
            `<div class="locked-overlay">
                <i class="fas fa-lock locked-icon"></i>
                <div class="locked-message">This feature is coming soon!</div>
                <a href="#" class="unlock-btn" onclick="alert('Oral tests will be available soon! Stay tuned for updates.'); return false;">
                    🔔 Notify Me When Available
                </a>
            </div>` : '';
        
        cardDiv.innerHTML = `
            <div class="test-card-content">
                <div class="test-card-header">
                    <span class="${badgeClass}">${test.badge}</span>
                    <div class="rating">
                        <span class="rating-score">${test.rating}</span>
                        <div>${starsHtml}</div>
                    </div>
                </div>
                <h3 class="test-card-title">${test.title} ${comingSoonHtml}</h3>
                <div class="test-stats">
                    <div class="test-stat">
                        <div class="test-stat-icon">
                            <i class="fas fa-question"></i>
                        </div>
                        <span class="test-stat-value">${test.questions} Questions</span>
                    </div>
                    <div class="test-stat">
                        <div class="test-stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <span class="test-stat-value">${test.duration}</span>
                    </div>
                    <div class="test-stat">
                        <div class="test-stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="test-stat-value">${test.attempted} attempted</span>
                    </div>
                </div>
                ${buttonHtml}
            </div>
            ${lockedOverlayHtml}
        `;
        
        return cardDiv;
    }
    
    // Initial render of written tests
    renderTestCards('written');
    
    // Add hover effects to test cards
    document.addEventListener('mouseover', function(e) {
        if (e.target.closest('.test-card')) {
            e.target.closest('.test-card').style.transform = 'translateY(-5px)';
        }
    });
    
    document.addEventListener('mouseout', function(e) {
        if (e.target.closest('.test-card')) {
            e.target.closest('.test-card').style.transform = 'translateY(0)';
        }
    });
});

