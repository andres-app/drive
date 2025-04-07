<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);

    // Verifica que el archivo sea del usuario
    $stmt = $conn->prepare("SELECT * FROM files WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $userId]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$file) {
        http_response_code(404);
        exit;
    }

    // Cambia el estado a inactivo (oculto)
    $stmt = $conn->prepare("UPDATE files SET activo = 0 WHERE id = ?");
    $stmt->execute([$id]);

    http_response_code(200);
    exit;
}
