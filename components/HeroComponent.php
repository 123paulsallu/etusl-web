<?php
// components/HeroComponent.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db.php';

// Handle add/update hero slide
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_hero'])) {
    $caption_heading = $_POST['caption_heading'] ?? '';
    $caption_level = $_POST['caption_level'] ?? 'h2';
    $caption_color = $_POST['caption_color'] ?? '#08204b';
    $description = $_POST['description'] ?? '';
    $description_level = $_POST['description_level'] ?? 'p';
    $description_color = $_POST['description_color'] ?? '#333';
    $status = $_POST['status'] === 'inactive' ? 'inactive' : 'active';
    $id = $_POST['id'] ?? '';
    $images = $_FILES['images'] ?? null;
    if ($id) {
        // Update (image optional)
        if ($images && $images['error'][0] === 0) {
            $upload_dir = __DIR__ . '/../assets/hero/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $img_name = uniqid('hero_') . '_' . basename($images['name'][0]);
            $img_path = $upload_dir . $img_name;
            move_uploaded_file($images['tmp_name'][0], $img_path);
            $img_db_path = 'assets/hero/' . $img_name;
            $stmt = $pdo->prepare('UPDATE hero_slides SET image=?, caption_heading=?, caption_level=?, caption_color=?, description=?, description_level=?, description_color=?, status=? WHERE id=?');
            $stmt->execute([$img_db_path, $caption_heading, $caption_level, $caption_color, $description, $description_level, $description_color, $status, $id]);
        } else {
            $stmt = $pdo->prepare('UPDATE hero_slides SET caption_heading=?, caption_level=?, caption_color=?, description=?, description_level=?, description_color=?, status=? WHERE id=?');
            $stmt->execute([$caption_heading, $caption_level, $caption_color, $description, $description_level, $description_color, $status, $id]);
        }
    } else if ($images && $images['error'][0] === 0) {
        // Add (multiple images)
        $upload_dir = __DIR__ . '/../assets/hero/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        foreach ($images['tmp_name'] as $i => $tmp_name) {
            $img_name = uniqid('hero_') . '_' . basename($images['name'][$i]);
            $img_path = $upload_dir . $img_name;
            move_uploaded_file($tmp_name, $img_path);
            $img_db_path = 'assets/hero/' . $img_name;
            $stmt = $pdo->prepare('INSERT INTO hero_slides (image, caption_heading, caption_level, caption_color, description, description_level, description_color, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$img_db_path, $caption_heading, $caption_level, $caption_color, $description, $description_level, $description_color, $status]);
        }
    }
    echo '<script>window.location.replace(window.location.href);</script>';
    exit;
}
// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare('DELETE FROM hero_slides WHERE id=?');
    $stmt->execute([$id]);
    echo '<script>window.location.replace(window.location.pathname);</script>';
    exit;
}
// Handle edit fetch
$edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $pdo->prepare('SELECT * FROM hero_slides WHERE id=?');
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
}
// Fetch all hero slides
$slides = $pdo->query('SELECT * FROM hero_slides ORDER BY id DESC')->fetchAll();
?>
<div class="hero-admin">
    <button class="add-btn" onclick="openHeroModal()">Add Hero Slide</button>
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
                    <div class="slide-actions">
                        <span class="status-<?php echo isset($slide['status']) ? htmlspecialchars($slide['status']) : 'active'; ?>">Status: <?php echo isset($slide['status']) ? ucfirst($slide['status']) : 'Active'; ?></span>
                        <a href="?edit=<?php echo $slide['id']; ?>" class="edit-btn">Edit</a>
                        <a href="?delete=<?php echo $slide['id']; ?>" class="delete-btn" onclick="return confirm('Delete this slide?');">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Modal for Add/Edit -->
    <div id="heroModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeHeroModal()">&times;</span>
            <h2 id="modalTitle">Add Hero Slide</h2>
            <form id="heroForm" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" id="modalId" value="">
                <label>Images (multiple allowed):
                    <input type="file" name="images[]" accept="image/*" id="modalImages" multiple>
                </label>
                <label>Caption Heading:
                    <input type="text" name="caption_heading" id="modalCaptionHeading" required>
                </label>
                <label>Heading Level:
                    <select name="caption_level" id="modalCaptionLevel">
                        <option value="h1">H1</option>
                        <option value="h2" selected>H2</option>
                        <option value="h3">H3</option>
                        <option value="h4">H4</option>
                        <option value="h5">H5</option>
                        <option value="h6">H6</option>
                    </select>
                </label>
                <label>Heading Color:
                    <input type="color" name="caption_color" id="modalCaptionColor" value="#08204b">
                </label>
                <label>Description:
                    <textarea name="description" id="modalDescription" rows="3" required></textarea>
                </label>
                <label>Description Level:
                    <select name="description_level" id="modalDescriptionLevel">
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
                    <input type="color" name="description_color" id="modalDescriptionColor" value="#333333">
                </label>
                <label>Status:
                    <select name="status" id="modalStatus">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </label>
                <button type="submit" name="save_hero">Save</button>
            </form>
        </div>
    </div>
</div>
<style>
.hero-admin {
    width: 100%;
    max-width: 700px;
    margin: 0 auto;
}
.add-btn {
    background: #08204b;
    color: #fff;
    border: none;
    border-radius: 0.25rem;
    padding: 0.7rem 1.5rem;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    margin-bottom: 1.5rem;
}
.add-btn:hover { background: #0a2a6c; }
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
    position: relative;
}
.slide-img {
    width: 100%;
    max-width: 180px;
    height: auto;
    border-radius: 0.5rem;
    margin-bottom: 0.5rem;
    object-fit: cover;
}
.slide-actions {
    margin-top: 0.7rem;
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
    align-items: center;
}
.edit-btn, .delete-btn {
    color: #08204b;
    text-decoration: underline;
    cursor: pointer;
    font-size: 0.98rem;
}
.delete-btn { color: #c0392b; }
.status-active { color: #27ae60; font-weight: 700; }
.status-inactive { color: #c0392b; font-weight: 700; }
/* Modal styles */
.modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100vw; height: 100vh; overflow: auto; background: rgba(8,32,75,0.25); }
.modal-content { background: #fff; margin: 5% auto; padding: 2rem; border-radius: 0.5rem; max-width: 400px; box-shadow: 0 4px 16px #08204b22; position: relative; }
.close { position: absolute; top: 1rem; right: 1rem; font-size: 1.5rem; color: #08204b; cursor: pointer; }
.hero-form label, .modal-content label { font-weight: 600; display: flex; flex-direction: column; gap: 0.3rem; }
.hero-form input[type="text"], .modal-content input[type="text"], .modal-content textarea, .modal-content select {
    padding: 0.5rem 1rem;
    border: 1px solid #cfd8dc;
    border-radius: 0.25rem;
    font-size: 1rem;
}
.hero-form input[type="color"], .modal-content input[type="color"] {
    width: 40px;
    height: 32px;
    border: none;
    background: none;
    padding: 0;
}
.hero-form button, .modal-content button[type="submit"] {
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
.hero-form button:hover, .modal-content button[type="submit"]:hover { background: #0a2a6c; }
@media (max-width: 600px) {
    .hero-admin { max-width: 100%; padding: 0 0.5rem; }
    .hero-form, .slide-item, .modal-content { padding: 1rem; }
    .slides-grid { grid-template-columns: 1fr; }
}
</style>
<script>
function openHeroModal(edit = null) {
    document.getElementById('heroModal').style.display = 'block';
    document.getElementById('modalTitle').textContent = edit ? 'Edit Hero Slide' : 'Add Hero Slide';
    document.getElementById('heroForm').querySelector('button[type="submit"]').textContent = edit ? 'Update' : 'Save';
    if (edit) {
        document.getElementById('modalId').value = edit.id;
        document.getElementById('modalCaptionHeading').value = edit.caption_heading;
        document.getElementById('modalCaptionLevel').value = edit.caption_level;
        document.getElementById('modalCaptionColor').value = edit.caption_color;
        document.getElementById('modalDescription').value = edit.description;
        document.getElementById('modalDescriptionLevel').value = edit.description_level;
        document.getElementById('modalDescriptionColor').value = edit.description_color;
        document.getElementById('modalStatus').value = edit.status;
        document.getElementById('modalImages').required = false;
    } else {
        document.getElementById('modalId').value = '';
        document.getElementById('modalCaptionHeading').value = '';
        document.getElementById('modalCaptionLevel').value = 'h2';
        document.getElementById('modalCaptionColor').value = '#08204b';
        document.getElementById('modalDescription').value = '';
        document.getElementById('modalDescriptionLevel').value = 'p';
        document.getElementById('modalDescriptionColor').value = '#333333';
        document.getElementById('modalStatus').value = 'active';
        document.getElementById('modalImages').required = true;
    }
}
function closeHeroModal() {
    document.getElementById('heroModal').style.display = 'none';
}
// Open modal for edit
<?php if ($edit): ?>
openHeroModal(<?php echo json_encode($edit); ?>);
<?php endif; ?>
// Close modal on outside click
window.onclick = function(event) {
    var modal = document.getElementById('heroModal');
    if (event.target == modal) {
        closeHeroModal();
    }
}
</script>
