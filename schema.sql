-- mysql -u root -p -h 127.0.0.1 -P 3306 --
CREATE Database touchIn if not exists;
CREATE TABLE users if not exists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    fingerprint_template TEXT,
    pin VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
 );