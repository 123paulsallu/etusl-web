<?php
// components/SidebarComponent.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit;
}
$username = $_SESSION['username'] ?? '';
$role = $_SESSION['role'] ?? '';
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-user">
            <div class="sidebar-username"><?php echo htmlspecialchars($username); ?></div>
            <div class="sidebar-role"><?php echo htmlspecialchars($role); ?></div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Users</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="../pages/logout.php">Logout</a></li>
        </ul>
    </nav>
</aside>
