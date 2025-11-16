<?php
// components/HeroComponent.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db.php';

// Handle add hero slide
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_hero'])) {
    $caption_heading = $_POST['caption_heading'] ?? '';
    $caption_level = $_POST['caption_level'] ?? 'h2';
    $caption_color = $_POST['caption_color'] ?? '#08204b';
    $description = $_POST['description'] ?? '';
    $description_level = $_POST['description_level'] ?? 'p';
    $description_color = $_POST['description_color'] ?? '#333';
    $images = $_FILES['images'] ?? null;
    if ($images && $images['error'][0] === 0) {
        $upload_dir = __DIR__ . '/../assets/hero/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        foreach ($images['tmp_name'] as $i => $tmp_name) {
            $img_name = uniqid('hero_') . '_' . basename($images['name'][$i]);
            $img_path = $upload_dir . $img_name;
            move_uploaded_file($tmp_name, $img_path);
            $img_db_path = 'assets/hero/' . $img_name;
            $stmt = $pdo->prepare('INSERT INTO hero_slides (image, caption_heading, caption_level, caption_color, description, description_level, description_color) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$img_db_path, $caption_heading, $caption_level, $caption_color, $description, $description_level, $description_color]);
        }
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}
// Fetch all hero slides
$slides = $pdo->query('SELECT * FROM hero_slides ORDER BY id DESC')->fetchAll();
?>
<div class="hero-admin">
    <form class="hero-form" method="post" enctype="multipart/form-data">
        <h2>Add Hero Slide</h2>
        <label>Images (multiple allowed):
            <input type="file" name="images[]" accept="image/*" multiple required>
        </label>
        <label>Caption Heading:
            <input type="text" name="caption_heading" required>
        </label>
        <label>Heading Level:
            <select name="caption_level">
                <option value="h1">H1</option>
                <option value="h2" selected>H2</option>
                <option value="h3">H3</option>
                <option value="h4">H4</option>
                <option value="h5">H5</option>
                <option value="h6">H6</option>
            </select>
        </label>
        <label>Heading Color:
            <input type="color" name="caption_color" value="#08204b">
        </label>
        <label>Description:
            <textarea name="description" rows="3" required></textarea>
        </label>
        <label>Description Level:
            <select name="description_level">
                <option value="h1">H1</option>
                <option value="h2">H2</option>
                <option value="h3">H3</option>
                <option value="h4">H4</option>
                <option value="h5">H5</option>
                <option value="h6">H6</option>
                <option value="p" selected>P</option>
            </select>
        </label>
        <label>Description Color:
            <input type="color" name="description_color" value="#333333">
        </label>
        <button type="submit" name="add_hero">Add Slide</button>
    </form>
    <div class="hero-slides-list">
        <h2>Hero Slides</h2>
        <div class="slides-grid">
            <?php foreach ($slides as $slide): ?>
                <div class="slide-item">
                    <img src="../<?php echo htmlspecialchars($slide['image']); ?>" alt="Hero Image" class="slide-img">
                    <<?php echo $slide['caption_level']; ?> style="color:<?php echo htmlspecialchars($slide['caption_color']); ?>; margin:0.5rem 0 0.2rem 0;">
                        <?php echo htmlspecialchars($slide['caption_heading']); ?>
                    </<?php echo $slide['caption_level']; ?>>
                    <<?php echo $slide['description_level']; ?> style="color:<?php echo htmlspecialchars($slide['description_color']); ?>; margin:0;">
                        <?php echo htmlspecialchars($slide['description']); ?>
                    </<?php echo $slide['description_level']; ?>>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<style>
.hero-admin {
    width: 100%;
    max-width: 700px;
    margin: 0 auto;
}
.hero-form {
    background: #f7faff;
    border: 1px solid #e0e0e0;
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.hero-form label {
    font-weight: 600;
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}
.hero-form input[type="text"],
.hero-form textarea,
.hero-form select {
    padding: 0.5rem 1rem;
    border: 1px solid #cfd8dc;
    border-radius: 0.25rem;
    font-size: 1rem;
}
.hero-form input[type="color"] {
    width: 40px;
    height: 32px;
    border: none;
    background: none;
    padding: 0;
}
.hero-form button {
    background: #08204b;
    color: #fff;
    border: none;
    border-radius: 0.25rem;
    padding: 0.7rem 1.5rem;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    margin-top: 0.5rem;
    align-self: flex-start;
}
.hero-form button:hover {
    background: #0a2a6c;
}
.hero-slides-list {
    margin-top: 2rem;
}
.slides-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.5rem;
}
.slide-item {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 0.5rem;
    padding: 1rem;
    text-align: center;
    box-shadow: 0 2px 8px #08204b11;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.slide-img {
    width: 100%;
    max-width: 180px;
    height: auto;
    border-radius: 0.5rem;
    margin-bottom: 0.5rem;
    object-fit: cover;
}
@media (max-width: 600px) {
    .hero-admin { max-width: 100%; padding: 0 0.5rem; }
    .hero-form, .slide-item { padding: 1rem; }
    .slides-grid { grid-template-columns: 1fr; }
}
</style>
