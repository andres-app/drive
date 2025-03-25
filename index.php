<?php
// Configuración de la base de datos
$host = "localhost";
$dbname = "drive";
$username = "root";
$password = "";

// Conectar a MySQL
$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$currentFolder = isset($_GET['folder']) ? $_GET['folder'] : '';

// Crear carpeta
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["new_folder"])) {
    $folderName = trim($_POST["new_folder"]);
    if (!empty($folderName)) {
        $stmt = $conn->prepare("INSERT INTO files (name, path, size, folder) VALUES (?, ?, ?, ?)");
        $stmt->execute([$folderName, "", 0, $currentFolder]);
    }
    header("Location: ?folder=$currentFolder");
    exit();
}

// Subir múltiples archivos
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
            $stmt = $conn->prepare("INSERT INTO files (name, path, size, folder) VALUES (?, ?, ?, ?)");
            $stmt->execute([$fileName, $filePath, $fileSize, $currentFolder]);
        }
    }
    header("Location: ?folder=$currentFolder");
    exit();
}

// Obtener carpetas y archivos
$stmt = $conn->prepare("SELECT * FROM files WHERE folder = ? ORDER BY size = 0 DESC, name ASC");
$stmt->execute([$currentFolder]);
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Drive</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #121212;
            color: white;
        }
        .container {
            max-width: 1200px;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .grid-item {
            background-color: #1e1e1e;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
        }
        .grid-item:hover {
            background-color: #292929;
            transform: scale(1.05);
        }
        .grid-item a {
            text-decoration: none;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .grid-item img {
            width: 60px;
            height: 60px;
            margin-bottom: 10px;
        }
        .btn-dark {
            background-color: #333;
            border: none;
        }
        .btn-dark:hover {
            background-color: #444;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <h2 class="text-center mb-4">📂 Mi Drive</h2>
        
        <div class="d-flex justify-content-between">
            <form method="POST" class="mb-3">
                <div class="input-group">
                    <input type="text" name="new_folder" class="form-control" placeholder="Nueva carpeta" required>
                    <button type="submit" class="btn btn-dark">Crear</button>
                </div>
            </form>
            <form method="POST" enctype="multipart/form-data" class="mb-3">
                <div class="input-group">
                    <input type="file" name="files[]" class="form-control" multiple required>
                    <button type="submit" class="btn btn-dark">Subir</button>
                </div>
            </form>
        </div>
        
        <?php if ($currentFolder): ?>
            <a href="?folder=" class="btn btn-warning mb-3">⬅ Volver</a>
        <?php endif; ?>
        
        <div class="grid-container">
            <?php foreach ($files as $file): ?>
                <div class="grid-item">
                    <a href="<?php echo $file['size'] == 0 ? '?folder=' . urlencode($file['name']) : htmlspecialchars($file['path']); ?>">
                        <img src="<?php echo $file['size'] == 0 ? 'https://cdn-icons-png.flaticon.com/512/3767/3767084.png' : 'https://cdn-icons-png.flaticon.com/512/3767/3767085.png'; ?>" alt="icon">
                        <strong><?php echo htmlspecialchars($file['name']); ?></strong>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
