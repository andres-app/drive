<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$currentFolder = isset($_GET['folder']) ? $_GET['folder'] : '';
$type = $_GET['type'] ?? '';
$fecha = $_GET['fecha'] ?? '';


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
    foreach ($_FILES["files"]["name"] as $key => $fullName) {
        $fileTmp = $_FILES["files"]["tmp_name"][$key];
        $fileSize = $_FILES["files"]["size"][$key];
        $cleanPath = str_replace(['../', './'], '', $fullName);
        $segments = explode('/', $cleanPath);
        $fileName = array_pop($segments);

        $parent = $currentFolder;

        // Crear cada carpeta del path (si no existe)
        foreach ($segments as $folder) {
            if ($folder === '' || $folder === '.') continue;

            $uploadPath = "uploads/" . ($parent ? $parent . '/' : '') . $folder;
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Revisar si ya está en base de datos
            $stmt = $conn->prepare("SELECT COUNT(*) FROM files WHERE name = ? AND folder = ? AND user_id = ? AND size = 0");
            $stmt->execute([$folder, $parent, $userId]);
            $exists = $stmt->fetchColumn();

            if (!$exists) {
                $stmt = $conn->prepare("INSERT INTO files (name, path, size, folder, user_id) VALUES (?, '', 0, ?, ?)");
                $stmt->execute([$folder, $parent, $userId]);
            }

            $parent = $folder; // profundiza a la siguiente subcarpeta
        }

        // Si no es un archivo válido, salta
        if (!is_uploaded_file($fileTmp)) continue;

        $finalFolder = $parent;
        $uploadFilePath = "uploads/" . ($finalFolder ? $finalFolder . '/' : '') . $fileName;

        if (!is_dir(dirname($uploadFilePath))) {
            mkdir(dirname($uploadFilePath), 0777, true);
        }

        if (move_uploaded_file($fileTmp, $uploadFilePath)) {
            $stmt = $conn->prepare("INSERT INTO files (name, path, size, folder, user_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$fileName, $uploadFilePath, $fileSize, $finalFolder, $userId]);
        }
    }

    header("Location: ?folder=$currentFolder");
    exit();
}




function getAllSubfolders($conn, $userId, $parentFolder)
{
    $folders = [$parentFolder];
    $queue = [$parentFolder];

    while (!empty($queue)) {
        $current = array_shift($queue);
        $stmt = $conn->prepare("SELECT name FROM files WHERE folder = ? AND user_id = ? AND size = 0 AND activo = 1");
        $stmt->execute([$current, $userId]);
        $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($results as $sub) {
            $folders[] = $sub;
            $queue[] = $sub;
        }
    }

    return $folders;
}


$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$type = $_GET['type'] ?? '';
$fecha = $_GET['fecha'] ?? '';

if ($search !== '') {
    $folders = getAllSubfolders($conn, $userId, $currentFolder);
    $placeholders = implode(',', array_fill(0, count($folders), '?'));

    $conditions = [
        "folder IN ($placeholders)",
        "user_id = ?",
        "activo = 1",
        "name LIKE ?"
    ];

    $params = array_merge($folders, [$userId, '%' . $search . '%']);

    // Filtro por tipo de archivo
    if ($type === 'image') {
        $conditions[] = "LOWER(name) REGEXP '\\.(jpg|jpeg|png|gif)$'";
    }
    if ($type === 'doc') {
        $conditions[] = "LOWER(name) REGEXP '\\.(pdf|docx?|xlsx?|pptx?)$'";
    }
    if ($type === 'media') {
        $conditions[] = "LOWER(name) REGEXP '\\.(mp3|mp4|wav|avi)$'";
    }
    if ($type === 'folder') {
        $conditions[] = "size = 0";
    }

    // Filtro por fecha
    if ($fecha === 'hoy') {
        $conditions[] = "DATE(created_at) = CURDATE()";
    }
    if ($fecha === 'semana') {
        $conditions[] = "YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)";
    }
    if ($fecha === 'mes') {
        $conditions[] = "MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
    }

    $query = "SELECT * FROM files WHERE " . implode(" AND ", $conditions) . " ORDER BY size = 0 DESC, name ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
} else {
    $stmt = $conn->prepare("SELECT * FROM files WHERE folder = ? AND user_id = ? AND activo = 1 ORDER BY size = 0 DESC, name ASC");
    $stmt->execute([$currentFolder, $userId]);
}

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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

        .file-name {
            max-width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }

        #contextMenu {
            width: 150px;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>📂 Mi Drive</h2>
        <div class="d-flex justify-content-center">
            <form method="POST" class="d-flex gap-2 w-100 align-items-center">
                <input type="text" name="new_folder" class="form-control h-100 py-2" style="height: 38px;" placeholder="Nueva carpeta" required>
                <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center px-3" style="height: 38px; border-radius: 0.375rem;">
                    <i class="bi bi-folder-plus"></i>
                </button>
            </form>
        </div>




        <button type="button" class="btn btn-sm w-100 modal-option" data-bs-toggle="modal" data-bs-target="#uploadModal">📄 Subir archivo</button>
        <div class="mt-auto">
            <a href="trash.php" class="btn btn-sm btn-outline-danger mt-2 w-100">🗑 Ver papelera</a>

            <hr class="bg-light">
            <p class="mb-1">👤 <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <a href="logout.php" class="btn btn-sm btn-warning mt-1 w-100">Cerrar sesión</a>
        </div>
    </div>

    <div class="main-content">
        <?php if ($currentFolder): ?>
            <a href="?folder=" class="btn btn-warning mb-3">⬅ Volver</a>
        <?php endif; ?>
        <div class="sticky-top bg-white py-3" style="z-index: 1020; border-bottom: 1px solid #dee2e6;">
            <form method="GET" class="row g-2 align-items-center mb-0 px-2" role="search">
                <input type="hidden" name="folder" value="<?= htmlspecialchars($currentFolder) ?>">

                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Buscar archivos o carpetas..." value="<?= htmlspecialchars($search ?? '') ?>">
                </div>

                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">Todos</option>
                        <option value="image" <?= $type == 'image' ? 'selected' : '' ?>>Imágenes</option>
                        <option value="doc" <?= $type == 'doc' ? 'selected' : '' ?>>Documentos</option>
                        <option value="media" <?= $type == 'media' ? 'selected' : '' ?>>Audio/Video</option>
                        <option value="folder" <?= $type == 'folder' ? 'selected' : '' ?>>Carpetas</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="fecha" class="form-select">
                        <option value="">Cualquier fecha</option>
                        <option value="hoy" <?= $fecha == 'hoy' ? 'selected' : '' ?>>Hoy</option>
                        <option value="semana" <?= $fecha == 'semana' ? 'selected' : '' ?>>Esta semana</option>
                        <option value="mes" <?= $fecha == 'mes' ? 'selected' : '' ?>>Este mes</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">🔍 Buscar</button>
                </div>
            </form>
        </div>


        <div class="grid-container">
            <?php foreach ($files as $file): ?>
                <div class="grid-item" data-id="<?= $file['id'] ?>" onmouseenter="startTooltipDelay(this)" onmouseleave="clearTooltipDelay(this)">
                    <div class="tooltip-upload-date">
                        <div><strong><?php echo htmlspecialchars($file['name']); ?></strong></div>
                        <div>Subido el: <?php echo date('d/m/Y H:i', strtotime($file['created_at'] ?? 'now')); ?></div>
                    </div>

                    <a href="<?php echo $file['size'] == 0 ? '?folder=' . urlencode($file['name']) : htmlspecialchars($file['path']); ?>">
                        <img src="<?php echo $file['size'] == 0
                                        ? 'https://cdn-icons-png.flaticon.com/512/3767/3767084.png'
                                        : getFileIcon($file['name']); ?>" alt="icono">
                        <?php
                        $highlightedName = htmlspecialchars($file['name']);
                        if ($search !== '') {

                            $highlightedName = preg_replace("/(" . preg_quote($search, '/') . ")/i", '<mark>$1</mark>', $highlightedName);
                        }
                        ?>
                        <strong class="file-name"><?= $highlightedName ?></strong>


                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="contextMenu" class="position-absolute bg-white border rounded shadow-sm p-2 d-none" style="z-index: 999;">
        <button class="btn btn-sm w-100 text-start" onclick="renameItem()">✏️ Renombrar</button>
        <button class="btn btn-sm w-100 text-start text-danger" onclick="deleteItem()">🗑 Eliminar</button>
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

    <!-- Modal Renombrar -->
    <div class="modal fade" id="renameModal" tabindex="-1" aria-labelledby="renameModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="renameForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="renameModalLabel">Renombrar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="renameId">
                        <div class="mb-3">
                            <label for="newFileName" class="form-label">Nuevo nombre</label>
                            <input type="text" class="form-control" id="newFileName" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let tooltipTimer;

        function startTooltipDelay(element) {
            tooltipTimer = setTimeout(() => {
                element.classList.add('hover-delay');
            }, 1000); // 1 segundo
        }

        function clearTooltipDelay(element) {
            clearTimeout(tooltipTimer);
            element.classList.remove('hover-delay');
        }

        let contextMenu = document.getElementById("contextMenu");
        let selectedItem = null;

        // Mostrar menú contextual al hacer clic derecho
        document.addEventListener("contextmenu", function(e) {
            const item = e.target.closest(".grid-item");
            if (item) {
                e.preventDefault();
                selectedItem = item;
                contextMenu.style.top = `${e.pageY}px`;
                contextMenu.style.left = `${e.pageX}px`;
                contextMenu.classList.remove("d-none");
            } else {
                contextMenu.classList.add("d-none");
            }
        });

        // Ocultar menú contextual al hacer clic fuera
        document.addEventListener("click", function() {
            contextMenu.classList.add("d-none");
        });

        // Modal de renombrar
        let renameModal = new bootstrap.Modal(document.getElementById('renameModal'));
        const renameForm = document.getElementById("renameForm");
        const renameIdInput = document.getElementById("renameId");
        const newFileNameInput = document.getElementById("newFileName");

        function renameItem() {
            const fileNameElement = selectedItem.querySelector(".file-name");
            const oldName = fileNameElement.textContent.trim();
            const fileId = selectedItem.getAttribute("data-id");

            renameIdInput.value = fileId;
            newFileNameInput.value = oldName;
            renameModal.show();
        }

        function deleteItem() {
            const fileId = selectedItem.getAttribute("data-id");

            if (confirm("¿Seguro que quieres eliminar este elemento?")) {
                const formData = new FormData();
                formData.append("delete_id", fileId);

                fetch("delete.php", {
                    method: "POST",
                    body: formData
                }).then(response => {
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        alert("Error al eliminar.");
                    }
                });
            }
        }


        // Enviar datos al backend al guardar cambios
        renameForm.addEventListener("submit", function(e) {
            e.preventDefault();

            const fileId = renameIdInput.value;
            let newName = newFileNameInput.value.trim();

            if (!newName) return;

            const fileNameElement = selectedItem.querySelector(".file-name");
            const oldName = fileNameElement.textContent.trim();

            // Validar extensión si es archivo (tiene punto y no empieza con punto)
            const hasExtension = oldName.includes('.') && !oldName.startsWith('.');
            if (hasExtension) {
                const oldExt = oldName.split('.').pop().toLowerCase();
                const newExt = newName.includes('.') ? newName.split('.').pop().toLowerCase() : '';

                if (newExt !== oldExt) {
                    // Si el usuario quitó o cambió la extensión, la corregimos
                    const baseName = newName.split('.')[0];
                    newName = baseName + '.' + oldExt;
                }
            }

            const formData = new FormData();
            formData.append("rename_id", fileId);
            formData.append("new_name", newName);

            fetch("rename.php", {
                method: "POST",
                body: formData
            }).then(response => {
                if (response.ok) {
                    renameModal.hide();
                    window.location.reload();
                } else {
                    alert("Error al renombrar.");
                }
            });
        });
    </script>

</body>

</html>