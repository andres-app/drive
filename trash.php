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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .file-icon {
            width: 50px;
            height: 50px;
        }

        .file-name {
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .checkbox-top {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .card-body {
            padding-top: 1rem;
        }
    </style>
</head>
<body>

<div class="container py-4">
    <a href="index.php" class="btn btn-outline-secondary mb-4">⬅ Volver al inicio</a>
    <h3 class="mb-4">🗑 Archivos en papelera</h3>

    <form method="POST" action="acciones_papelera.php">
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <div>
                <button type="submit" name="accion" value="restaurar" class="btn btn-success me-2">🔄 Restaurar seleccionados</button>
                <button type="submit" name="accion" value="eliminar" class="btn btn-danger" onclick="return confirm('¿Estás seguro de vaciar la papelera? Esta acción no se puede deshacer.');">🗑 Vaciar papelera</button>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach ($deletedFiles as $file): ?>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card bg-white position-relative h-100 text-center p-2">
                        <input type="checkbox" name="ids[]" value="<?= $file['id'] ?>" class="form-check-input checkbox-top">
                        <img src="<?= getFileIcon($file['name']) ?>" alt="icono" class="file-icon mx-auto mt-3">
                        <div class="card-body">
                            <div class="file-name" title="<?= htmlspecialchars($file['name']) ?>">
                                <?= htmlspecialchars($file['name']) ?>
                            </div>
                            <div class="text-muted small mt-1">
                                <?= date('d/m/Y H:i', strtotime($file['created_at'])) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </form>
</div>

</body>
</html>
