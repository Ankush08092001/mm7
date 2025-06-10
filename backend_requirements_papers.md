# Backend Requirements for Papers Page

This document outlines the backend functionalities required to manage the content on the "Papers" page of the MarineMonks website. The goal is to enable administrators to easily upload, categorize, and manage PDF papers without direct code modifications.

## 1. Paper Management API/Admin Interface

An administrative interface (e.g., a web-based admin panel) or a set of API endpoints should be developed to perform the following operations:

### 1.1. Upload New PDF Papers
- **Endpoint/Functionality**: A secure mechanism to upload PDF files.
- **Metadata Capture**: For each uploaded PDF, the system must capture and store the following metadata:
    - **File (PDF)**: The actual PDF file.
    - **Paper Title**: A descriptive title for the paper (e.g., "MEK-G March 2025").
    - **Subject**: One of the predefined subject categories:
        - MEK-G (Marine Engineering Knowledge – General)
        - MEK-M (Marine Engineering Knowledge – Motor)
        - MET (Marine Electrical Technology)
        - MEP (Marine Engineering Practice)
        - NAVAL (Naval Architecture & Stability)
        - SSEP (Ship Safety & Environmental Protection)
    - **Year**: The year the paper belongs to (e.g., 2025, 2024, 2023).
    - **Topic**: A specific topic related to the paper (e.g., Pumps, Fire Safety, Turbochargers, Stability, Bearings). This should ideally be a free-text field or a selection from a predefined, extensible list.
    - **Upload Date (Optional)**: Automatically recorded upon upload, but should be editable.
    - **Tags (Optional)**: Multiple tags (e.g., “Frequently Asked”, “Sketch”, “Numerical”). This should support adding new tags dynamically.
    - **Coming Soon Flag**: A boolean flag to mark a paper as "Coming Soon" (true/false).
    - **Featured Flag (Optional)**: A boolean flag to mark a paper as "Featured" for prominent display.
    - **Visibility Status**: A flag to hide or unpublish a paper from the frontend without deleting it.

### 1.2. Edit Existing Paper Details
- **Endpoint/Functionality**: Ability to modify any of the metadata associated with an uploaded paper (title, subject, year, topic, date, tags, coming soon, featured, visibility).
- **File Replacement**: Option to replace the PDF file associated with a paper.

### 1.3. Delete Papers
- **Endpoint/Functionality**: Securely delete a paper and its associated PDF file from the system.

### 1.4. List/Search Papers
- **Endpoint/Functionality**: Retrieve a list of all uploaded papers with their metadata.
- **Filtering/Sorting**: Ability to filter by subject, year, topic, or search by title/tags.

## 2. Data Storage

### 2.1. Database Schema (Example using SQL)

A database table (e.g., `papers`) would be required to store the metadata for each paper. The PDF files themselves should be stored in a file storage system (e.g., local disk, S3 bucket) and their paths referenced in the database.

```sql
CREATE TABLE papers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    subject ENUM(
        'MEK-G', 'MEK-M', 'MET', 'MEP', 'NAVAL', 'SSEP'
    ) NOT NULL,
    year INT NOT NULL,
    topic VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL, -- Path to the stored PDF file
    upload_date DATE DEFAULT CURRENT_DATE,
    tags JSON, -- Store as JSON array for multiple tags
    is_coming_soon BOOLEAN DEFAULT FALSE,
    is_featured BOOLEAN DEFAULT FALSE,
    is_published BOOLEAN DEFAULT TRUE, -- True for visible, False for hidden
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Example for tags table if not using JSON
-- CREATE TABLE paper_tags (
--     paper_id INT,
--     tag_name VARCHAR(100),
--     PRIMARY KEY (paper_id, tag_name),
--     FOREIGN KEY (paper_id) REFERENCES papers(id) ON DELETE CASCADE
-- );
```

### 2.2. File Storage
- PDFs should be stored in a dedicated, secure directory on the server or a cloud storage solution.
- File names should be unique (e.g., using UUIDs or a combination of title and timestamp) to prevent conflicts.

## 3. Frontend Integration (API Consumption)

### 3.1. Data Fetching
- The frontend JavaScript (as implemented in `papers.html`) will need to fetch paper data from a backend API endpoint.
- This endpoint should return a JSON array of paper objects, containing all necessary metadata (id, title, subject, year, topic, file_path, upload_date, tags, is_coming_soon, is_featured).

### 3.2. Filtering and Searching
- The frontend will handle client-side filtering and searching based on the fetched data.
- For very large datasets, server-side filtering/searching might be considered to optimize performance.

### 3.3. PDF Serving
- The backend should serve the PDF files securely. This can be done by:
    - Directly serving static files if they are in a publicly accessible directory.
    - Implementing a dedicated endpoint that retrieves the file from storage and serves it, which allows for access control and tracking.

## 4. Security Considerations

- **Authentication & Authorization**: The admin panel/API endpoints for managing papers must be secured with proper authentication and authorization mechanisms to ensure only authorized users can perform operations.
- **File Upload Validation**: Implement robust validation for uploaded files (e.g., file type, size limits) to prevent malicious uploads.
- **Input Sanitization**: Sanitize all user inputs to prevent SQL injection, XSS, and other vulnerabilities.
- **Error Handling**: Implement comprehensive error handling and logging for all backend operations.

## 5. Technology Stack (Recommendations)

- **Backend Framework**: Python (Flask/Django), Node.js (Express), Ruby on Rails, etc.
- **Database**: PostgreSQL, MySQL, SQLite.
- **File Storage**: Local filesystem, AWS S3, Google Cloud Storage.

This backend structure will provide a robust and flexible system for managing the content on your "Papers" page, allowing for easy updates and categorization as per your requirements.

