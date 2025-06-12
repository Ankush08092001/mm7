-- Create database if not exists
CREATE DATABASE IF NOT EXISTS marinemonks;
USE marinemonks;

-- Admin users table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('super_admin', 'admin') NOT NULL DEFAULT 'admin',
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Study materials table
CREATE TABLE IF NOT EXISTS study_materials (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    type ENUM('written', 'orals') NOT NULL,
    subject VARCHAR(100),
    function VARCHAR(100),
    topic VARCHAR(255),
    author VARCHAR(100),
    tags TEXT,
    file_path VARCHAR(255) NOT NULL,
    is_coming_soon BOOLEAN DEFAULT FALSE,
    is_pro_only BOOLEAN DEFAULT FALSE,
    upload_count INT DEFAULT 0,
    download_count INT DEFAULT 0,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id)
);

-- Probables table
CREATE TABLE IF NOT EXISTS probables (
    id INT PRIMARY KEY AUTO_INCREMENT,
    year INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    is_coming_soon BOOLEAN DEFAULT FALSE,
    upload_count INT DEFAULT 0,
    download_count INT DEFAULT 0,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id)
);

-- Mock tests table
CREATE TABLE IF NOT EXISTS mock_tests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    type ENUM('probables_based', 'non_repeated', 'full_ability') NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    reference_topic VARCHAR(255),
    reference_subject VARCHAR(100),
    status ENUM('active', 'scheduled', 'inactive') DEFAULT 'active',
    scheduled_date DATETIME,
    upload_count INT DEFAULT 0,
    download_count INT DEFAULT 0,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id)
);

-- Answer submissions table
CREATE TABLE IF NOT EXISTS answer_submissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    mock_test_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    status ENUM('pending', 'checked') DEFAULT 'pending',
    marks INT,
    feedback TEXT,
    checked_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (mock_test_id) REFERENCES mock_tests(id),
    FOREIGN KEY (checked_by) REFERENCES admin_users(id)
);

-- Users table (if not already exists)
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    is_pro BOOLEAN DEFAULT FALSE,
    subscription_end_date DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Analytics table
CREATE TABLE IF NOT EXISTS analytics (
    id INT PRIMARY KEY AUTO_INCREMENT,
    content_type ENUM('study_material', 'probables', 'mock_test') NOT NULL,
    content_id INT NOT NULL,
    action_type ENUM('view', 'download') NOT NULL,
    user_id INT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Settings table
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(50) UNIQUE NOT NULL,
    setting_value TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO admin_users (username, password, email, role) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@marinemonks.in', 'super_admin');

-- Insert default settings
INSERT INTO settings (setting_key, setting_value) VALUES
('upload_limit', '10'),
('allowed_file_types', 'pdf,jpg,jpeg,png'),
('max_file_size', '10485760'); -- 10MB in bytes

