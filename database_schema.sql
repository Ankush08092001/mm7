CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    is_premium_member BOOLEAN DEFAULT FALSE
);

CREATE TABLE IF NOT EXISTS probables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    year INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    views INT DEFAULT 0,
    downloads INT DEFAULT 0,
    coming_soon BOOLEAN DEFAULT FALSE
);

CREATE TABLE IF NOT EXISTS study_materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subject_function VARCHAR(255) NOT NULL,
    topic VARCHAR(255),
    author VARCHAR(255),
    type ENUM("written", "orals") NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    views INT DEFAULT 0,
    downloads INT DEFAULT 0,
    coming_soon BOOLEAN DEFAULT FALSE
);

CREATE TABLE IF NOT EXISTS mock_tests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    test_type ENUM("easy", "medium", "hard") NOT NULL,
    questions TEXT,
    upload_path VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS answersheets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    test_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    feedback TEXT,
    status ENUM("pending", "checked") DEFAULT "pending",
    marks INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (test_id) REFERENCES mock_tests(id)
);

