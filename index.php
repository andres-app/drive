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
        $filePath = $uploadDir . basename($fileName);

        if (move_uploaded_file($fileTmp, $filePath)) {
            $stmt = $conn->prepare("INSERT INTO files (name, path, size, folder, user_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$fileName, $filePath, $fileSize, $currentFolder, $userId]);
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

        .sidebar form .btn {
            background-color: #90caf9;
            color: #0d47a1;
            font-weight: bold;
            border: none;
        }

        .sidebar form .btn:hover {
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
        <form method="POST" class="d-flex align-items-center gap-2">
            <input type="text" name="new_folder" class="form-control" placeholder="Nueva carpeta" required>
            <button type="submit" class="btn btn-sm">+</button>
        </form>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="files[]" class="form-control mb-2" multiple required>
            <button type="submit" class="btn btn-sm w-100">Subir archivo</button>
        </form>
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
                <div class="grid-item">
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
</body>

</html>
