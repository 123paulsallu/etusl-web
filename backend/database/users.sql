CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('super admin', 'web master', 'admin', 'PRO Unit', 'Faculty Admin', 'registry') NOT NULL DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password, role)
VALUES (
    'ETUSL001',
    '$2y$10$ynHur1owUaZIPWbhrxKUlOfdtX8OVRX5ykD0uRS6DJTskg1VcOej2', -- Replace with actual hash
    'super admin'
);