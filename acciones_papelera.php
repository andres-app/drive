<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$accion = $_POST['accion'] ?? null;
$ids = $_POST['ids'] ?? [];

if (!empty($ids) && is_array($ids)) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    if ($accion === 'restaurar') {
        $stmt = $conn->prepare("UPDATE files SET activo = 1 WHERE user_id = ? AND id IN ($placeholders)");
        $stmt->execute(array_merge([$userId], $ids));

    } elseif ($accion === 'eliminar') {
        // Cambia el estado a -1 (definitivamente no visible)
        $stmt = $conn->prepare("UPDATE files SET activo = -1 WHERE user_id = ? AND id IN ($placeholders)");
        $stmt->execute(array_merge([$userId], $ids));
    }
}

header("Location: trash.php");
exit;
