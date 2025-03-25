<?php
// Determinar el entorno (cambia a 'production' en el servidor)
$environment = 'production'; 

// Configuración de conexión según el entorno
$config = [
    'development' => [
        'host' => 'localhost',
        'dbname' => 'drive',
        'username' => 'root',
        'password' => '',
    ],
    'production' => [
        'host' => 'localhost',
        'dbname' => 'u274409976_drive',
        'username' => 'u274409976_drive',
        'password' => 'Redes2804751$$$',
    ]
];

// Obtener configuración actual
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
    die("Error de conexión: " . $e->getMessage());
}
?>
