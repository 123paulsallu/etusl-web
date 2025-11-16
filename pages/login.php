<?php
// Handle login POST
session_start();
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../components/db.php';
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username && $password) {
        $stmt = $pdo->prepare('SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header('Location: ../pages/admin-dashboard.php');
            exit;
        } else {
            $login_error = 'Invalid username or password.';
        }
    } else {
        $login_error = 'Please enter both username and password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ETUSL</title>
    <link rel="stylesheet" href="../styles/logo.css">
    <style>
        body {
            min-height: 100vh;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            font-family: 'Quicksand', Arial, sans-serif;
        }
        .login-card {
            background: #fff;
            color: #08204b;
            padding: 2.5rem 2rem 2rem 2rem;
            min-width: 320px;
            box-shadow: 0 8px 32px 0 rgba(8,32,75,0.18);
            border-radius: 0;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            animation: fadeInUp 0.8s cubic-bezier(.39,.575,.565,1) both;
            align-items: stretch;
        }
        .login-card .logo-center {
            display: flex;
            justify-content: center;
            margin-bottom: 1.2rem;
        }
        .login-card h2 {
            margin: 0 0 1rem 0;
            font-family: 'Italiana', 'Quicksand', Arial, sans-serif;
            font-size: 2rem;
            text-align: center;
            letter-spacing: 1px;
        }
        .login-card label {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .login-card input[type="text"],
        .login-card input[type="password"] {
            width: 100%;
            box-sizing: border-box;
            padding: 0.75rem 1rem;
            border: 1px solid #cfd8dc;
            border-radius: 0.25rem;
            font-size: 1rem;
            background: #f7faff;
            color: #08204b;
            transition: box-shadow 0.2s, border-color 0.2s;
            outline: none;
            margin-bottom: 0.5rem;
        }
        .login-card input[type="text"]:focus,
        .login-card input[type="password"]:focus {
            border-color: #08204b;
            box-shadow: 0 0 0 2px #08204b33;
        }
        .login-card button {
            background: #08204b;
            color: #fff;
            border: none;
            border-radius: 0.25rem;
            padding: 0.75rem 1rem;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
            box-shadow: 0 2px 8px 0 rgba(8,32,75,0.10);
        }
        .login-card button:hover, .login-card button:focus {
            background: #0a2a6c;
            transform: translateY(-2px) scale(1.03);
        }
        .login-error {
            color: #c0392b;
            background: #ffeaea;
            border: 1px solid #f5c6cb;
            border-radius: 0.25rem;
            padding: 0.5rem 1rem;
            margin-bottom: 0.5rem;
            text-align: center;
            font-size: 1rem;
        }
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <form class="login-card" method="post" action="">
        <div class="logo-center">
            <?php include __DIR__ . '/../components/LogoComponent.html'; ?>
        </div>
        <h2>Login</h2>
        <?php if ($login_error): ?>
            <div class="login-error"><?php echo htmlspecialchars($login_error); ?></div>
        <?php endif; ?>
        <div>
            <label for="username">User Name</label>
            <input type="text" id="username" name="username" required autocomplete="username">
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
        </div>
        <button type="submit">Sign In</button>
    </form>
</body>
</html>
