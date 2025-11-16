CREATE TABLE hero_slides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image VARCHAR(255) NOT NULL,
    caption_heading TEXT,
    caption_level ENUM('h1','h2','h3','h4','h5','h6') DEFAULT 'h2',
    caption_color VARCHAR(20) DEFAULT '#08204b',
    description TEXT,
    description_level ENUM('h1','h2','h3','h4','h5','h6','p') DEFAULT 'p',
    description_color VARCHAR(20) DEFAULT '#333',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
