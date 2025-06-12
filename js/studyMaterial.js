// Study Material JavaScript functionality
class StudyMaterialManager {
    constructor() {
        this.currentTab = 'written';
        this.writtenMaterials = [];
        this.oralsMaterials = [];
        this.filteredWrittenMaterials = [];
        this.filteredOralsMaterials = [];
        
        this.backendUrl = '/backend/study_materials.php'; // Updated backend URL
        
        this.init();
        this.fetchMaterials();
    }

    init() {
        // Tab switching functionality
        document.querySelectorAll('.study-tab').forEach(tab => {
            tab.addEventListener('click', (e) => {
                this.switchTab(e.target.dataset.tab);
            });
        });

        // Search functionality
        document.getElementById('written-search').addEventListener('input', () => {
            this.fetchMaterials('written');
        });
        
        document.getElementById('orals-search').addEventListener('input', () => {
            this.fetchMaterials('orals');
        });

        // Filter functionality
        document.getElementById('written-subject').addEventListener('change', () => {
            this.updateTopics('written');
            this.fetchMaterials('written');
        });
        
        document.getElementById('written-topic').addEventListener('change', () => {
            this.fetchMaterials('written');
        });
        
        document.getElementById('written-author').addEventListener('change', () => {
            this.fetchMaterials('written');
        });

        document.getElementById('orals-function').addEventListener('change', () => {
            this.fetchMaterials('orals');
        });
        
        document.getElementById('orals-topic').addEventListener('change', () => {
            this.fetchMaterials('orals');
        });
        
        document.getElementById('orals-author').addEventListener('change', () => {
            this.fetchMaterials('orals');
        });
    }

    switchTab(tabName) {
        // Update tab appearance
        document.querySelectorAll('.study-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');

        // Update content visibility
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        document.getElementById(`${tabName}-content`).classList.add('active');

        this.currentTab = tabName;
        this.fetchMaterials(tabName);
    }

    async fetchMaterials(type = this.currentTab) {
        let url;
        let params = new URLSearchParams();

        if (type === 'written') {
            const searchTerm = document.getElementById('written-search').value;
            const selectedSubject = document.getElementById('written-subject').value;
            const selectedTopic = document.getElementById('written-topic').value;
            const selectedAuthor = document.getElementById('written-author').value;

            url = `${this.backendUrl}/written_materials`;
            if (searchTerm) params.append('search', searchTerm);
            if (selectedSubject) params.append('subject', selectedSubject);
            if (selectedTopic) params.append('topic', selectedTopic);
            if (selectedAuthor) params.append('author', selectedAuthor);

        } else if (type === 'orals') {
            const searchTerm = document.getElementById('orals-search').value;
            const selectedFunction = document.getElementById('orals-function').value;
            const selectedTopic = document.getElementById('orals-topic').value;
            const selectedAuthor = document.getElementById('orals-author').value;

            url = `${this.backendUrl}/orals_materials`;
            if (searchTerm) params.append('search', searchTerm);
            if (selectedFunction) params.append('function', selectedFunction);
            if (selectedTopic) params.append('topic', selectedTopic);
            if (selectedAuthor) params.append('author', selectedAuthor);
        }

        try {
            const response = await fetch(`${url}?${params.toString()}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            
            if (type === 'written') {
                this.writtenMaterials = data;
                this.filteredWrittenMaterials = data;
            } else {
                this.oralsMaterials = data;
                this.filteredOralsMaterials = data;
            }
            this.renderMaterials(type);
            this.updateTopics(type);
        } catch (error) {
            console.error(`Error fetching ${type} materials:`, error);
            const gridId = type === 'written' ? 'written-materials-grid' : 'orals-materials-grid';
            document.getElementById(gridId).innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #777;">Failed to load materials. Please try again later.</div>';
        }
    }

    updateTopics(type) {
        if (type === 'written') {
            const subjectSelect = document.getElementById('written-subject');
            const topicSelect = document.getElementById('written-topic');
            const selectedSubject = subjectSelect.value;

            // Clear existing options
            topicSelect.innerHTML = '<option value="">All Topics</option>';

            if (selectedSubject) {
                // Get unique topics for the selected subject from the fetched data
                const topics = [...new Set(
                    this.writtenMaterials
                        .filter(material => material.subject === selectedSubject && material.topic)
                        .map(material => material.topic)
                )].sort();

                topics.forEach(topic => {
                    const option = document.createElement('option');
                    option.value = topic;
                    option.textContent = topic;
                    topicSelect.appendChild(option);
                });
            }
        } else if (type === 'orals') {
            const functionSelect = document.getElementById('orals-function');
            const topicSelect = document.getElementById('orals-topic');
            const selectedFunction = functionSelect.value;

            // Clear existing options
            topicSelect.innerHTML = '<option value="">All Topics</option>';

            if (selectedFunction) {
                // Get unique topics for the selected function from the fetched data
                const topics = [...new Set(
                    this.oralsMaterials
                        .filter(material => material.function === selectedFunction && material.topic)
                        .map(material => material.topic)
                )].sort();

                topics.forEach(topic => {
                    const option = document.createElement('option');
                    option.value = topic;
                    option.textContent = topic;
                    topicSelect.appendChild(option);
                });
            }
        }
    }

    renderMaterials(type) {
        const gridId = type === 'written' ? 'written-materials-grid' : 'orals-materials-grid';
        const grid = document.getElementById(gridId);
        const materials = type === 'written' ? this.writtenMaterials : this.oralsMaterials;

        grid.innerHTML = '';

        if (materials.length === 0) {
            grid.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #777;">No materials found matching your criteria.</div>';
            return;
        }

        materials.forEach(material => {
            const card = this.createMaterialCard(material, type);
            grid.appendChild(card);
        });
    }

    createMaterialCard(material, type) {
        const card = document.createElement('div');
        card.className = 'study-card animate-on-scroll';

        if (type === 'written') {
            card.innerHTML = `
                <div class="study-card-content">
                    <div style="margin-bottom: 15px;">
                        <span class="function-tag">${material.subject}</span>
                    </div>
                    <h3 class="study-card-title">${material.title}</h3>
                    <p class="study-card-subtitle">${material.topic || 'General'}</p>
                    <p class="study-card-author">By ${material.author}</p>
                    <div class="study-card-meta">
                        <span>${material.pages || 'N/A'} pages</span>
                        <span>${material.downloads} downloads</span>
                    </div>
                    <div class="study-card-actions">
                        <a href="#" class="preview-btn" onclick="studyMaterialManager.previewMaterial('${material.file_path}')">
                            <i class="fas fa-eye"></i> Preview
                        </a>
                        <a href="#" class="download-btn" onclick="studyMaterialManager.downloadMaterial('${material.file_path}')">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                </div>
            `;
        } else {
            card.innerHTML = `
                <div class="study-card-content">
                    <div style="margin-bottom: 15px;">
                        <span class="function-tag">${material.function}</span>
                    </div>
                    <h3 class="study-card-title">${material.question_type}</h3>
                    <p class="study-card-subtitle">${material.topic || 'General Questions'}</p>
                    <p class="study-card-author">By ${material.author}</p>
                    <div class="study-card-meta">
                        <span>Uploaded: ${new Date(material.upload_date).toLocaleDateString()}</span>
                        <span>${material.downloads} downloads</span>
                    </div>
                    <div class="study-card-actions">
                        <a href="#" class="preview-btn" onclick="studyMaterialManager.previewMaterial('${material.file_path}')">
                            <i class="fas fa-eye"></i> Preview
                        </a>
                        <a href="#" class="download-btn" onclick="studyMaterialManager.downloadMaterial('${material.file_path}')">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                </div>
            `;
        }

        return card;
    }

    previewMaterial(filePath) {
        window.open(`${this.backendUrl}/download/${filePath.split('/').pop()}`, '_blank');
    }

    downloadMaterial(filePath) {
        const link = document.createElement('a');
        link.href = `${this.backendUrl}/download/${filePath.split('/').pop()}`;
        link.download = filePath.split('/').pop();
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}

// Initialize the study material manager when the page loads
let studyMaterialManager;
document.addEventListener('DOMContentLoaded', () => {
    studyMaterialManager = new StudyMaterialManager();
});

