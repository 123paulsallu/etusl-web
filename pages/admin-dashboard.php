<?php
// pages/admin-dashboard.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
// Get user info from session
$username = $_SESSION['username'] ?? '';
$role = $_SESSION['role'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ETUSL</title>
    <link rel="stylesheet" href="../styles/logo.css">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            background: #f4f7fa;
            font-family: 'Quicksand', Arial, sans-serif;
        }
        .sidebar {
            width: 240px;
            background: #08204b;
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 8px 0 rgba(8,32,75,0.08);
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
        .dashboard-content {
            flex: 1;
            padding: 2rem;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../components/SidebarComponent.php'; ?>
    <main class="dashboard-content">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <p>Your role: <strong><?php echo htmlspecialchars($role); ?></strong></p>
        <p>This is the admin dashboard. Add your dashboard widgets and content here.</p>
    </main>
</body>
</html>
