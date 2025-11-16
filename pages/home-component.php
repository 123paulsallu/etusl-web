<?php
// pages/home-component.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Management</title>
    <link rel="stylesheet" href="../styles/logo.css">
    <style>
        body { font-family: 'Quicksand', Arial, sans-serif; background: #f4f7fa; margin: 0; }
        .layout { display: flex; min-height: 100vh; }
        .main-content { flex: 1; max-width: 900px; margin: 2rem auto; background: #fff; border-radius: 0.5rem; box-shadow: 0 4px 16px #08204b22; padding: 2rem; }
        .tabs { display: flex; gap: 2rem; border-bottom: 2px solid #e0e0e0; margin-bottom: 2rem; }
        .tab { padding: 0.7rem 1.5rem; cursor: pointer; font-weight: 600; color: #08204b; border: none; background: none; border-radius: 0.25rem 0.25rem 0 0; transition: background 0.2s, color 0.2s; }
        .tab.active, .tab:hover { background: #08204b; color: #fff; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .hero-section { background: #e3eafc; padding: 2rem; border-radius: 0.5rem; text-align: center; margin-bottom: 2rem; }
        .hero-section h1 { color: #08204b; font-size: 2.2rem; margin-bottom: 1rem; }
        .hero-section p { color: #333; font-size: 1.2rem; }
        .sections-list { display: flex; flex-direction: column; gap: 1.5rem; }
        .section-item { background: #f7faff; border: 1px solid #e0e0e0; border-radius: 0.5rem; padding: 1.2rem; }
        .section-item h2 { color: #08204b; margin: 0 0 0.5rem 0; }
    </style>
</head>
<body>
    <div class="layout">
        <?php include __DIR__ . '/../components/SidebarComponent.php'; ?>
        <div class="main-content">
            <div class="tabs">
                <button class="tab active" onclick="showTab('hero')">Hero</button>
                <button class="tab" onclick="showTab('sections')">Sections</button>
            </div>
            <div id="hero" class="tab-content active">
                <div class="hero-section">
                    <h1>Welcome to the Hero Section</h1>
                    <p>This is the hero area. You can manage the main banner, headline, and call-to-action here.</p>
                </div>
            </div>
            <div id="sections" class="tab-content">
                <div class="sections-list">
                    <div class="section-item">
                        <h2>Section 1</h2>
                        <p>Content for section 1 goes here.</p>
                    </div>
                    <div class="section-item">
                        <h2>Section 2</h2>
                        <p>Content for section 2 goes here.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function showTab(tabId) {
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(tc => tc.classList.remove('active'));
            document.querySelector('.tab[onclick*="' + tabId + '"]').classList.add('active');
            document.getElementById(tabId).classList.add('active');
        }
    </script>
</body>
</html>
