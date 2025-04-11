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
    <title>Papelera - Mi Drive</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f8;
            font-family: 'Segoe UI', sans-serif;
            color: #333;
        }

        .container {
            max-width: 1200px;
        }

        .file-card {
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease-in-out;
            padding: 20px 10px;
            text-align: center;
            height: 100%;
            position: relative;
        }

        .file-card:hover {
            transform: scale(1.02);
        }

        .file-card img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .file-name {
            font-weight: 500;
            font-size: 0.95rem;
            margin-top: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .file-date {
            font-size: 0.75rem;
            color: #888;
            margin-top: 4px;
        }

        .checkbox-top {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .top-actions {
            background-color: #fff;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .btn {
            border-radius: 10px;
            font-weight: 500;
        }

        .btn i {
            margin-right: 6px;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <a href="index.php" class="btn btn-outline-secondary mb-4"><i class="bi bi-arrow-left"></i> Volver al inicio</a>
    <h3 class="mb-4 fw-semibold">🗑 Archivos en papelera</h3>

    <form method="POST" action="acciones_papelera.php">
        <div class="top-actions d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <button type="submit" name="accion" value="restaurar" class="btn btn-success me-2">
                    <i class="bi bi-arrow-counterclockwise"></i> Restaurar seleccionados
                </button>
                <button type="submit" name="accion" value="eliminar" class="btn btn-danger"
                    onclick="return confirm('¿Estás seguro de vaciar la papelera? Esta acción no se puede deshacer.');">
                    <i class="bi bi-trash-fill"></i> Vaciar papelera
                </button>
            </div>
        </div>

        <div class="row g-4 mt-2">
            <?php foreach ($deletedFiles as $file): ?>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="file-card">
                        <input type="checkbox" name="ids[]" value="<?= $file['id'] ?>" class="form-check-input checkbox-top">
                        <img src="<?= getFileIcon($file['name']) ?>" alt="icono">
                        <div class="file-name" title="<?= htmlspecialchars($file['name']) ?>">
                            <?= htmlspecialchars($file['name']) ?>
                        </div>
                        <div class="file-date">
                            <?= date('d/m/Y H:i', strtotime($file['created_at'])) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </form>
</div>

</body>
</html>
