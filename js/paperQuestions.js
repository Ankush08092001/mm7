// Paper Questions JavaScript - Handles individual question display page
document.addEventListener('DOMContentLoaded', function() {
    // Get paper ID from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const paperId = urlParams.get('id');
    
    // DOM Elements
    const paperTitle = document.getElementById('paperTitle');
    const paperSubtitle = document.getElementById('paperSubtitle');
    const paperMetaInfo = document.getElementById('paperMetaInfo');
    const questionsContent = document.getElementById('questionsContent');
    const previewPdfBtn = document.getElementById('previewPdfBtn');
    const downloadPdfBtn = document.getElementById('downloadPdfBtn');
    
    // Initialize the page
    init();
    
    function init() {
        if (paperId) {
            loadPaper(paperId);
        } else {
            showError('No paper ID provided');
        }
        
        setupPdfButtons();
    }
    
    function loadPaper(paperId) {
        // Find the paper in the data
        const paper = papersData.find(p => p.id === paperId);
        
        if (!paper) {
            showError('Paper not found');
            return;
        }
        
        // Update page title and meta info
        updatePaperInfo(paper);
        
        // Display questions
        displayQuestions(paper);
    }
    
    function updatePaperInfo(paper) {
        // Update page title
        document.title = `${paper.title} - MarineMonks`;
        
        // Update header content
        paperTitle.textContent = paper.title;
        paperSubtitle.textContent = `All Questions for ${paper.subject} – ${paper.month} ${paper.year}`;
        
        // Update meta information
        const metaHtml = `
            <div class="meta-item">
                <i class="fas fa-calendar"></i>
                <span>${paper.date}</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-book"></i>
                <span>${paper.subject}</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-tag"></i>
                <span>${paper.topic}</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-file-pdf"></i>
                <span>PDF Available</span>
            </div>
        `;
        
        paperMetaInfo.innerHTML = metaHtml;
    }
    
    function displayQuestions(paper) {
        if (!paper.questions || paper.questions.length === 0) {
            showNoQuestions();
            return;
        }
        
        let html = '';
        
        paper.questions.forEach((question, index) => {
            html += `
                <div class="question-card">
                    <div class="question-header-card">
                        <div class="question-number">${question.q_num}</div>
                        <div class="question-marks">${question.marks} marks</div>
                    </div>
                    <div class="question-content">
                        <div class="question-text">${question.text}</div>
                        
                        ${question.subparts && question.subparts.length > 1 ? `
                            <div class="question-subparts">
                                <h4>Subparts:</h4>
                                ${question.subparts.map(subpart => `
                                    <div class="subpart">
                                        <div class="subpart-label">Part (${subpart})</div>
                                    </div>
                                `).join('')}
                            </div>
                        ` : ''}
                        
                        ${question.tags && question.tags.length > 0 ? `
                            <div class="question-tags">
                                ${question.tags.map(tag => `
                                    <span class="question-tag ${tag.toLowerCase().replace(' ', '-')}">${tag}</span>
                                `).join('')}
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
        });
        
        questionsContent.innerHTML = html;
    }
    
    function showNoQuestions() {
        const html = `
            <div class="no-questions">
                <i class="fas fa-file-alt"></i>
                <h3>Questions Not Available</h3>
                <p>Detailed questions for this paper are not yet available. You can still preview and download the PDF.</p>
            </div>
        `;
        
        questionsContent.innerHTML = html;
    }
    
    function showError(message) {
        paperTitle.textContent = 'Error';
        paperSubtitle.textContent = message;
        
        const html = `
            <div class="no-questions">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Error Loading Paper</h3>
                <p>${message}</p>
                <a href="papers_new.html" class="action-btn primary" style="margin-top: 20px;">
                    <i class="fas fa-arrow-left"></i> Back to Papers
                </a>
            </div>
        `;
        
        questionsContent.innerHTML = html;
    }
    
    function setupPdfButtons() {
        previewPdfBtn.addEventListener('click', function() {
            const paper = getCurrentPaper();
            if (paper) {
                previewPdf(paper.file);
            }
        });
        
        downloadPdfBtn.addEventListener('click', function() {
            const paper = getCurrentPaper();
            if (paper) {
                downloadPdf(paper.file);
            }
        });
    }
    
    function getCurrentPaper() {
        const urlParams = new URLSearchParams(window.location.search);
        const paperId = urlParams.get('id');
        return papersData.find(p => p.id === paperId);
    }
    
    function previewPdf(filePath) {
        // This would open a PDF preview modal or new tab
        console.log('Preview PDF:', filePath);
        alert('PDF preview functionality would be implemented here');
    }
    
    function downloadPdf(filePath) {
        // This would trigger a download
        console.log('Download PDF:', filePath);
        alert('PDF download functionality would be implemented here');
    }
});

