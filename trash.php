<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM files WHERE user_id = ? AND activo = 0 ORDER BY created_at DESC");
$stmt->execute([$userId]);
$deletedFiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getFileIcon($fileName)
{
    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $icons = [
        'pdf' => 'https://cdn-icons-png.flaticon.com/512/337/337946.png',
        'doc' => 'https://cdn-icons-png.flaticon.com/512/732/732052.png',
        'docx' => 'https://cdn-icons-png.flaticon.com/512/732/732052.png',
        'xls' => 'https://cdn-icons-png.flaticon.com/512/732/732220.png',
        'xlsx' => 'https://cdn-icons-png.flaticon.com/512/732/732220.png',
        'ppt' => 'https://cdn-icons-png.flaticon.com/512/732/732076.png',
        'pptx' => 'https://cdn-icons-png.flaticon.com/512/732/732076.png',
        'jpg' => 'https://cdn-icons-png.flaticon.com/512/136/136524.png',
        'jpeg' => 'https://cdn-icons-png.flaticon.com/512/136/136524.png',
        'png' => 'https://cdn-icons-png.flaticon.com/512/136/136524.png',
        'gif' => 'https://cdn-icons-png.flaticon.com/512/136/136530.png',
        'txt' => 'https://cdn-icons-png.flaticon.com/512/3022/3022259.png',
        'zip' => 'https://cdn-icons-png.flaticon.com/512/6861/6861248.png',
        'rar' => 'https://cdn-icons-png.flaticon.com/512/7100/7100970.png',
        'mp4' => 'https://cdn-icons-png.flaticon.com/512/1179/1179069.png',
        'mp3' => 'https://cdn-icons-png.flaticon.com/512/727/727245.png',
    ];
    return $icons[$extension] ?? 'https://cdn-icons-png.flaticon.com/512/833/833524.png';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Papelera</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .grid-item {
            background-color: #fff8f8;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            text-align: center;
            padding: 20px;
            position: relative;
        }

        .grid-item img {
            width: 60px;
            height: 60px;
        }

        .restore-btn {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .file-name {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }
    </style>
</head>
<body class="p-4">

    <a href="index.php" class="btn btn-secondary mb-3">⬅ Volver al inicio</a>
    <h3 class="mb-4">🗑 Archivos en papelera</h3>

    <div class="grid-container">
        <?php foreach ($deletedFiles as $file): ?>
            <div class="grid-item">
                <form method="POST" action="restore.php" class="restore-btn">
                    <input type="hidden" name="restore_id" value="<?= $file['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-success">🔄</button>
                </form>
                <img src="<?= getFileIcon($file['name']) ?>" alt="icono">
                <strong class="file-name"><?= htmlspecialchars($file['name']) ?></strong>
                <div style="font-size: 0.8rem;">Eliminado el: <?= date('d/m/Y H:i', strtotime($file['created_at'])) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
