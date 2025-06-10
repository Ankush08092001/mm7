// Papers Flow JavaScript - Handles the new navigation structure
document.addEventListener('DOMContentLoaded', function() {
    let currentSubject = null;
    let currentStep = 'subjects'; // 'subjects', 'months', 'questions'
    
    // DOM Elements
    const subjectSelection = document.getElementById('subjectSelection');
    const monthSelection = document.getElementById('monthSelection');
    const selectedSubjectName = document.getElementById('selectedSubjectName');
    const yearMonthGrid = document.getElementById('yearMonthGrid');
    const backToSubjects = document.getElementById('backToSubjects');
    const topicGrid = document.getElementById('topicGrid');
    const topicResults = document.getElementById('topicResults');
    const topicResultsTitle = document.getElementById('topicResultsTitle');
    const topicPapersList = document.getElementById('topicPapersList');
    
    // Subject tab buttons
    const subjectTabBtns = document.querySelectorAll('.subject-tab-btn');
    
    // Initialize the page
    init();
    
    function init() {
        setupSubjectTabs();
        setupBackButton();
        populateTopics();
        setupTopicCards();
    }
    
    function setupSubjectTabs() {
        subjectTabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const subject = this.getAttribute('data-subject');
                selectSubject(subject);
            });
        });
    }
    
    function selectSubject(subject) {
        currentSubject = subject;
        currentStep = 'months';
        
        // Update UI
        updateActiveSubjectTab(subject);
        selectedSubjectName.textContent = subject;
        
        // Show month selection, hide subject selection
        subjectSelection.classList.add('hidden');
        monthSelection.classList.remove('hidden');
        
        // Populate year/month grid for selected subject
        populateYearMonthGrid(subject);
    }
    
    function updateActiveSubjectTab(subject) {
        subjectTabBtns.forEach(btn => {
            btn.classList.remove('active');
            if (btn.getAttribute('data-subject') === subject) {
                btn.classList.add('active');
            }
        });
    }
    
    function populateYearMonthGrid(subject) {
        // Filter papers by subject
        const subjectPapers = papersData.filter(paper => paper.subject === subject);
        
        // Group by year
        const yearGroups = {};
        subjectPapers.forEach(paper => {
            if (!yearGroups[paper.year]) {
                yearGroups[paper.year] = [];
            }
            yearGroups[paper.year].push(paper);
        });
        
        // Sort years in descending order
        const sortedYears = Object.keys(yearGroups).sort((a, b) => b - a);
        
        // Generate HTML
        let html = '';
        sortedYears.forEach(year => {
            html += `
                <div class="year-group">
                    <div class="year-header">${year}</div>
                    <div class="months-list">
            `;
            
            // Sort months within year (most recent first)
            const monthOrder = ['December', 'November', 'October', 'September', 'August', 'July', 'June', 'May', 'April', 'March', 'February', 'January'];
            const sortedPapers = yearGroups[year].sort((a, b) => {
                return monthOrder.indexOf(a.month) - monthOrder.indexOf(b.month);
            });
            
            sortedPapers.forEach(paper => {
                html += `
                    <div class="month-item" data-paper-id="${paper.id}">
                        <span class="month-name">${paper.month}</span>
                        <span class="month-arrow"><i class="fas fa-chevron-right"></i></span>
                    </div>
                `;
            });
            
            html += `
                    </div>
                </div>
            `;
        });
        
        yearMonthGrid.innerHTML = html;
        
        // Add click handlers to month items
        const monthItems = document.querySelectorAll('.month-item');
        monthItems.forEach(item => {
            item.addEventListener('click', function() {
                const paperId = this.getAttribute('data-paper-id');
                openQuestionPage(paperId);
            });
        });
    }
    
    function openQuestionPage(paperId) {
        // For now, we'll create a new page URL
        // In a real implementation, this could be a separate HTML file or dynamic routing
        const questionPageUrl = `paper-questions.html?id=${paperId}`;
        window.open(questionPageUrl, '_blank');
    }
    
    function setupBackButton() {
        backToSubjects.addEventListener('click', function() {
            currentStep = 'subjects';
            currentSubject = null;
            
            // Show subject selection, hide month selection
            subjectSelection.classList.remove('hidden');
            monthSelection.classList.add('hidden');
            
            // Clear active subject tab
            subjectTabBtns.forEach(btn => btn.classList.remove('active'));
        });
    }
    
    function populateTopics() {
        // Extract unique topics from papers data
        const topics = [...new Set(papersData.map(paper => paper.topic))];
        topics.sort();
        
        let html = '';
        topics.forEach(topic => {
            html += `
                <div class="topic-card" data-topic="${topic}">
                    ${topic}
                </div>
            `;
        });
        
        topicGrid.innerHTML = html;
    }
    
    function setupTopicCards() {
        // Add event delegation for topic cards
        topicGrid.addEventListener('click', function(e) {
            if (e.target.classList.contains('topic-card')) {
                const topic = e.target.getAttribute('data-topic');
                showTopicResults(topic);
                
                // Update active topic card
                document.querySelectorAll('.topic-card').forEach(card => {
                    card.classList.remove('active');
                });
                e.target.classList.add('active');
            }
        });
    }
    
    function showTopicResults(topic) {
        // Filter papers by topic
        const topicPapers = papersData.filter(paper => paper.topic === topic);
        
        topicResultsTitle.textContent = `Papers related to "${topic}"`;
        
        let html = '';
        topicPapers.forEach(paper => {
            html += `
                <div class="paper-item">
                    <h4>${paper.title}</h4>
                    <div class="paper-meta">
                        <span><i class="fas fa-calendar"></i> ${paper.date}</span>
                        <span><i class="fas fa-book"></i> ${paper.subject}</span>
                        <span><i class="fas fa-tag"></i> ${paper.topic}</span>
                    </div>
                    <div class="paper-tags">
                        ${paper.tags.map(tag => `<span class="tag">${tag}</span>`).join('')}
                    </div>
                    <div class="paper-actions">
                        <button class="preview-btn" onclick="previewPaper('${paper.id}')">
                            <i class="fas fa-eye"></i> Preview
                        </button>
                        <button class="download-btn" onclick="downloadPaper('${paper.file}')">
                            <i class="fas fa-download"></i> Download PDF
                        </button>
                        <button class="preview-btn" onclick="openQuestionPage('${paper.id}')">
                            <i class="fas fa-list"></i> View Questions
                        </button>
                    </div>
                </div>
            `;
        });
        
        topicPapersList.innerHTML = html;
        topicResults.classList.remove('hidden');
    }
    
    // Global functions for paper actions
    window.previewPaper = function(paperId) {
        const paper = papersData.find(p => p.id === paperId);
        if (paper) {
            // This would open a PDF preview modal
            console.log('Preview paper:', paper.title);
            alert('PDF preview functionality would be implemented here');
        }
    };
    
    window.downloadPaper = function(filePath) {
        // This would trigger a download
        console.log('Download paper:', filePath);
        alert('PDF download functionality would be implemented here');
    };
    
    window.openQuestionPage = function(paperId) {
        const questionPageUrl = `paper-questions.html?id=${paperId}`;
        window.open(questionPageUrl, '_blank');
    };
});

