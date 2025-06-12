-- Create database if not exists
CREATE DATABASE IF NOT EXISTS u301363515_marinemonks;
USE u301363515_marinemonks;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    is_pro_member BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Study materials table
CREATE TABLE IF NOT EXISTS study_materials (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    type ENUM('written', 'oral') NOT NULL,
    subject VARCHAR(100) NOT NULL,
    function VARCHAR(100),
    topic VARCHAR(100),
    author VARCHAR(100),
    file_path VARCHAR(255) NOT NULL,
    tags TEXT,
    download_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Probables table
CREATE TABLE IF NOT EXISTS probables (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    year INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    coming_soon BOOLEAN DEFAULT FALSE,
    download_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Mock tests table
CREATE TABLE IF NOT EXISTS mock_tests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    test_type ENUM('probables', 'non_repeated', 'full_ability') NOT NULL,
    question_path VARCHAR(255) NOT NULL,
    duration INT NOT NULL COMMENT 'Duration in minutes',
    is_pro_only BOOLEAN DEFAULT FALSE,
    status ENUM('draft', 'active', 'archived') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Answer sheets table
CREATE TABLE IF NOT EXISTS answersheets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    test_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    marks DECIMAL(5,2),
    feedback TEXT,
    status ENUM('pending', 'checked') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (test_id) REFERENCES mock_tests(id) ON DELETE CASCADE
);

-- User materials table (for tracking downloads)
CREATE TABLE IF NOT EXISTS user_materials (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    material_id INT NOT NULL,
    material_type ENUM('study', 'probable') NOT NULL,
    downloaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User progress table
CREATE TABLE IF NOT EXISTS user_progress (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    tests_attempted INT DEFAULT 0,
    materials_downloaded INT DEFAULT 0,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Settings table
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT INTO settings (setting_key, setting_value) VALUES
('site_name', 'Marine Monks'),
('site_description', 'Your Ultimate Exam Preparation Platform'),
('site_url', 'https://marinemonks.in'),
('admin_email', 'admin@marinemonks.in'),
('support_email', 'support@marinemonks.in'),
('maintenance_mode', 'false'),
('registration_enabled', 'true'),
('smtp_host', 'smtp.marinemonks.in'),
('smtp_port', '587'),
('smtp_username', 'noreply@marinemonks.in'),
('smtp_password', ''),
('smtp_encryption', 'tls');

-- Create indexes for better performance
CREATE INDEX idx_study_materials_type ON study_materials(type);
CREATE INDEX idx_study_materials_subject ON study_materials(subject);
CREATE INDEX idx_probables_year ON probables(year);
CREATE INDEX idx_mock_tests_type ON mock_tests(test_type);
CREATE INDEX idx_answersheets_status ON answersheets(status);
CREATE INDEX idx_user_materials_type ON user_materials(material_type);

-- Create admin user (password: admin123)
INSERT INTO users (name, email, password_hash, is_pro_member) VALUES
('Admin User', 'admin@marinemonks.in', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', TRUE);

