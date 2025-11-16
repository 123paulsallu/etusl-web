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
                    <li><a href="#">Header</a></li>
                    <li><a href="#">Pages</a></li>
                    <li><a href="#">Footer</a></li>
                </ul>
            </li>
            <li><a href="../pages/logout.php">Logout</a></li>
        </ul>
    </nav>
    <style>
        .sidebar-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-nav li {
            margin: 1rem 0;
            position: relative;
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
</aside>
