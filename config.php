<?php
// Detectar si estamos en localhost o en un servidor en línea
$serverName = $_SERVER['SERVER_NAME'] ?? 'localhost';
$isLocalhost = in_array($serverName, ['localhost', '127.0.0.1']);

// Configuración de conexión según el entorno detectado
$config = [
    'development' => [
        'host' => 'localhost',
        'dbname' => 'drive',
        'username' => 'root',  // ⚠️ Cambia si tu entorno local usa otro usuario
        'password' => '',      // ⚠️ Si MySQL local tiene contraseña, agrégala aquí
    ],
    'production' => [
        'host' => 'localhost',
        'dbname' => 'u274409976_drive',
        'username' => 'u274409976_drive',
        'password' => 'Redes2804751$$$',
    ]
];

// Seleccionar configuración automáticamente
$dbConfig = $isLocalhost ? $config['development'] : $config['production'];

// Conectar a MySQL con PDO
try {
    $conn = new PDO(
        "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset=utf8",
        $dbConfig['username'],
        $dbConfig['password']
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión en " . ($isLocalhost ? 'desarrollo' : 'producción') . ": " . $e->getMessage());
}
?>
