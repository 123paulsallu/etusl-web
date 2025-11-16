<?php
// components/TopbarComponent.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit;
}
require_once __DIR__ . '/db.php';
// Fetch topbar info from DB
$stmt = $pdo->query('SELECT email, location, address FROM topbar LIMIT 1');
$topbar = $stmt->fetch();
$email = $topbar['email'] ?? '';
$location = $topbar['location'] ?? '';
$address = $topbar['address'] ?? '';
?>
<div class="topbar-component">
    <span><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></span> |
    <span><strong>Location:</strong> <?php echo htmlspecialchars($location); ?></span> |
    <span><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></span>
</div>
<style>
.topbar-component {
    background: #0a2a6c;
    color: #fff;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    display: flex;
    gap: 1.5rem;
    align-items: center;
    border-radius: 0.25rem;
    margin-bottom: 1rem;
}
.topbar-component span {
    white-space: nowrap;
}
</style>
