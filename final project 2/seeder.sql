CREATE DATABASE IF NOT EXISTS diary_db;
USE diary_db;

CREATE TABLE IF NOT EXISTS entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    entry TEXT NOT NULL,
    date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO entries (title, entry, date) VALUES
('Test Entry 1', 'This is my first entry.', '2025-04-15'),
('Test Entry 2', 'This is my second entry.', '2025-04-16');
