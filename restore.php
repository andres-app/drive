<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restore_id'])) {
    $id = intval($_POST['restore_id']);

    $stmt = $conn->prepare("UPDATE files SET activo = 1 WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $userId]);

    header("Location: trash.php");
    exit;
}
