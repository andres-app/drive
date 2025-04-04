<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$currentFolder = isset($_GET['folder']) ? $_GET['folder'] : '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["new_folder"])) {
    $folderName = trim($_POST["new_folder"]);
    if (!empty($folderName)) {
        $stmt = $conn->prepare("INSERT INTO files (name, path, size, folder, user_id) VALUES (?, '', 0, ?, ?)");
        $stmt->execute([$folderName, $currentFolder, $userId]);
    }
    header("Location: ?folder=$currentFolder");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["files"])) {
    $uploadDir = "uploads/" . ($currentFolder ? $currentFolder . '/' : '');

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    foreach ($_FILES["files"]["name"] as $key => $fileName) {
        $fileTmp = $_FILES["files"]["tmp_name"][$key];
        $fileSize = $_FILES["files"]["size"][$key];

        $relativePath = $_FILES["files"]["name"][$key];
        $relativePath = str_replace(['../', './'], '', $relativePath);
        $fullPath = $uploadDir . $relativePath;
        $folderPath = dirname($relativePath);

        if (!empty($folderPath)) {
            $segments = explode('/', $folderPath);
            $parent = $currentFolder;
            $pathBuilder = $uploadDir;

            foreach ($segments as $folder) {
                $pathBuilder .= $folder . '/';
                if (!is_dir($pathBuilder)) {
                    mkdir($pathBuilder, 0777, true);
                }
                $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM files WHERE name = ? AND folder = ? AND user_id = ? AND size = 0");
                $stmtCheck->execute([$folder, $parent, $userId]);
                $exists = $stmtCheck->fetchColumn();

                if (!$exists) {
                    $stmtInsert = $conn->prepare("INSERT INTO files (name, path, size, folder, user_id) VALUES (?, '', 0, ?, ?)");
                    $stmtInsert->execute([$folder, $parent, $userId]);
                }
                $parent = $folder;
            }
        }

        $finalFolder = basename($folderPath);
        if ($finalFolder === '.' || $finalFolder === '') {
            $finalFolder = $currentFolder;
        }

        if (is_uploaded_file($fileTmp)) {
            if (!is_dir(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0777, true);
            }

            if (move_uploaded_file($fileTmp, $fullPath)) {
                $stmt = $conn->prepare("INSERT INTO files (name, path, size, folder, user_id) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([basename($fileName), $fullPath, $fileSize, $finalFolder, $userId]);
            }
        }
    }

    header("Location: ?folder=$currentFolder");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM files WHERE folder = ? AND user_id = ? ORDER BY size = 0 DESC, name ASC");
$stmt->execute([$currentFolder, $userId]);
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Mi Drive</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9fbfc;
            color: #444;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #e3f2fd;
            color: #0d47a1;
            position: fixed;
            top: 0;
            left: 0;
            padding: 2rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            border-right: 1px solid #bbdefb;
        }

        .sidebar h2 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .sidebar form .form-control {
            margin-bottom: 0.5rem;
        }

        .sidebar form .btn,
        .modal-option {
            background-color: #90caf9;
            color: #0d47a1;
            font-weight: bold;
            border: none;
        }

        .sidebar form .btn:hover,
        .modal-option:hover {
            background-color: #64b5f6;
            color: white;
        }

        .main-content {
            margin-left: 270px;
            padding: 2rem;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .grid-item {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            text-align: center;
            padding: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
        }

        .grid-item:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        .grid-item a {
            text-decoration: none;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .grid-item img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .tooltip-upload-date {
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s;
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: #fff;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            white-space: nowrap;
            z-index: 10;
        }

        .grid-item.hover-delay .tooltip-upload-date {
            visibility: visible;
            opacity: 1;
        }

        .btn-warning {
            background-color: #ffecb3;
            color: #6d4c41;
            border: none;
        }

        .btn-warning:hover {
            background-color: #ffe082;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>📂 Mi Drive</h2>
        <div class="d-flex justify-content-center">
            <form method="POST" class="d-flex align-items-center gap-2 w-100">
                <input type="text" name="new_folder" class="form-control" placeholder="Nueva carpeta" required>
                <button type="submit" class="btn btn-sm d-flex align-items-center justify-content-center" style="height: 100%; aspect-ratio: 1 / 1; padding: 0;">+</button>
            </form>
        </div>
        <button type="button" class="btn btn-sm w-100 modal-option" data-bs-toggle="modal" data-bs-target="#uploadModal">📄 Subir archivo</button>
        <div class="mt-auto">
            <hr class="bg-light">
            <p class="mb-1">👤 <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <a href="logout.php" class="btn btn-sm btn-warning mt-1 w-100">Cerrar sesión</a>
        </div>
    </div>

    <div class="main-content">
        <?php if ($currentFolder): ?>
            <a href="?folder=" class="btn btn-warning mb-3">⬅ Volver</a>
        <?php endif; ?>

        <div class="grid-container">
            <?php foreach ($files as $file): ?>
                <div class="grid-item" onmouseenter="startTooltipDelay(this)" onmouseleave="clearTooltipDelay(this)">
                    <div class="tooltip-upload-date">
                        Subido el: <?php echo date('d/m/Y H:i', strtotime($file['created_at'] ?? 'now')); ?>
                    </div>
                    <a href="<?php echo $file['size'] == 0 ? '?folder=' . urlencode($file['name']) : htmlspecialchars($file['path']); ?>">
                        <img src="<?php echo $file['size'] == 0
                                        ? 'https://cdn-icons-png.flaticon.com/512/3767/3767084.png'
                                        : getFileIcon($file['name']); ?>" alt="icono">
                        <strong><?php echo htmlspecialchars($file['name']); ?></strong>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Selecciona una opción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body d-flex flex-column gap-2">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="file" name="files[]" id="uploadFiles" class="d-none" multiple onchange="this.form.submit()">
                        <label for="uploadFiles" class="modal-option btn w-100 text-start">📄 Subir archivo</label>
                    </form>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="file" name="files[]" id="uploadFolder" class="d-none" webkitdirectory directory multiple onchange="this.form.submit()">
                        <label for="uploadFolder" class="modal-option btn w-100 text-start">📁 Subir carpeta</label>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let tooltipTimer;

        function startTooltipDelay(element) {
            tooltipTimer = setTimeout(() => {
                element.classList.add('hover-delay');
            }, 1000); // 1 segundos
        }

        function clearTooltipDelay(element) {
            clearTimeout(tooltipTimer);
            element.classList.remove('hover-delay');
        }
    </script>
</body>

</html>
