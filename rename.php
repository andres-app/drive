<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rename_id'], $_POST['new_name'])) {
    $id = intval($_POST['rename_id']);
    $newName = trim($_POST['new_name']);

    if ($newName === '') {
        http_response_code(400);
        exit;
    }

    // Validar que el archivo sea del usuario actual
    $stmt = $conn->prepare("SELECT * FROM files WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $userId]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$file) {
        http_response_code(404);
        exit;
    }

    // Si es archivo físico, renombrar también en disco
    if ($file['size'] > 0 && is_file($file['path'])) {
        $oldPath = $file['path'];
        $dir = dirname($oldPath);
        $newPath = $dir . '/' . $newName;

        if (!rename($oldPath, $newPath)) {
            http_response_code(500);
            exit;
        }

        $stmt = $conn->prepare("UPDATE files SET name = ?, path = ? WHERE id = ?");
        $stmt->execute([$newName, $newPath, $id]);
    } else {
        // Si es carpeta, solo actualiza el nombre (no existe en disco)
        $stmt = $conn->prepare("UPDATE files SET name = ? WHERE id = ?");
        $stmt->execute([$newName, $id]);
    }

    http_response_code(200);
    exit;
}
