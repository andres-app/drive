<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: index.php');
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Mi Drive</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Tipografía moderna -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        :root {
            --bg-color: #f8fafc;
            --box-color: #ffffff;
            --text-color: #1f2937;
            --input-bg: #f1f5f9;
            --input-border: #cbd5e1;
            --btn-bg: #3b82f6;
            --btn-hover: #2563eb;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg-color: #121212;
                --box-color: #1e1e1e;
                --text-color: #f3f4f6;
                --input-bg: #2c2c2c;
                --input-border: #444;
                --btn-bg: #3b82f6;
                --btn-hover: #2563eb;
            }
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-box {
            background-color: var(--box-color);
            padding: 2rem;
            border-radius: 16px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 12px 32px rgba(0,0,0,0.08);
        }

        .form-control {
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            padding: 0.75rem;
            border-radius: 12px;
            color: var(--text-color);
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--btn-bg);
            box-shadow: 0 0 0 0.15rem rgba(59, 130, 246, 0.25);
        }

        .btn-login {
            background-color: var(--btn-bg);
            color: white;
            font-weight: 600;
            padding: 0.75rem;
            border: none;
            border-radius: 12px;
            transition: background-color 0.2s ease;
        }

        .btn-login:hover {
            background-color: var(--btn-hover);
        }

        .form-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .form-group input {
            padding-left: 2.5rem;
        }

        .text-center small {
            font-size: 0.85rem;
            color: #9ca3af;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <h3 class="text-center mb-4">🔐 Iniciar sesión</h3>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger text-center py-2">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <div class="form-group">
                <i class="bi bi-person-fill form-icon"></i>
                <input type="text" name="username" class="form-control" placeholder="Usuario" required>
            </div>

            <div class="form-group">
                <i class="bi bi-lock-fill form-icon"></i>
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
            </div>

            <button type="submit" class="btn btn-login w-100">Entrar</button>
        </form>

        <div class="text-center mt-3">
            <small>© <?= date('Y') ?> Mi Drive</small>
        </div>
    </div>

</body>
</html>
