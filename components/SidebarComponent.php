<?php
// components/SidebarComponent.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
            <li class="dropdown">
                <a href="#" class="dropdown-toggle">Web Management &#9662;</a>
                <ul class="dropdown-menu">
                    <li><a href="../pages/topbar-crud.php">Top Bar</a></li>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Footer</a></li>
                </ul>
            </li>
            <li><a href="../pages/login.php">Logout</a></li>
        </ul>
    </nav>
</aside>
<style>
.sidebar {
    width: 240px;
    background: #08204b;
    color: #fff;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    box-shadow: 2px 0 8px 0 rgba(8,32,75,0.08);
    position: relative;
    z-index: 100;
}
.sidebar-header {
    padding: 2rem 1rem 1rem 1rem;
    border-bottom: 1px solid #0a2a6c;
}
.sidebar-user {
    text-align: center;
}
.sidebar-username {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}
.sidebar-role {
    font-size: 1rem;
    color: #b0c4e7;
}
.sidebar-nav {
    flex: 1;
    margin-top: 2rem;
}
.sidebar-nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.sidebar-nav li {
    margin: 1rem 0;
    position: relative;
}
.sidebar-nav a {
    color: #fff;
    text-decoration: none;
    font-size: 1.05rem;
    padding: 0.5rem 1.5rem;
    display: block;
    border-radius: 0.25rem;
    transition: background 0.2s;
}
.sidebar-nav a:hover {
    background: #0a2a6c;
}
.dropdown-menu {
    display: none;
    position: absolute;
    left: 100%;
    top: 0;
    background: #0a2a6c;
    min-width: 150px;
    box-shadow: 2px 2px 8px rgba(8,32,75,0.10);
    border-radius: 0.25rem;
    z-index: 10;
}
.dropdown:hover > .dropdown-menu {
    display: block;
}
.dropdown-menu li {
    margin: 0;
}
.dropdown-menu a {
    padding: 0.5rem 1rem;
    color: #fff;
    display: block;
    text-decoration: none;
    font-size: 1rem;
    border-bottom: 1px solid #123;
}
.dropdown-menu a:last-child {
    border-bottom: none;
}
.dropdown-toggle:after {
    content: '';
}
</style>
