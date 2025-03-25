<?php
// Detectar si estamos en localhost o en un servidor
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    $environment = 'development';
} else {
    $environment = 'production';
}

// Configuración de conexión según el entorno
$config = [
    'development' => [
        'host' => 'localhost',
        'dbname' => 'drive',
        'username' => 'root',  // ⚠️ Asegúrate de que este usuario existe en tu MySQL local
        'password' => '',      // ⚠️ Si tu MySQL local tiene contraseña, agrégala aquí
    ],
    'production' => [
        'host' => 'localhost',
        'dbname' => 'u274409976_drive',
        'username' => 'u274409976_drive',
        'password' => 'Redes2804751$$$',
    ]
];

// Obtener la configuración actual
$dbConfig = $config[$environment];

// Conectar a MySQL
try {
    $conn = new PDO(
        "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset=utf8",
        $dbConfig['username'],
        $dbConfig['password']
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión en $environment: " . $e->getMessage());
}
?>
