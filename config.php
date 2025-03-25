<?php
// Determinar el entorno (cambia a 'production' en el servidor)
$environment = 'development'; 

// Configuración de conexión según el entorno
$config = [
    'development' => [
        'host' => 'localhost',
        'dbname' => 'drive',
        'username' => 'root',  // ⚠️ Verifica que este usuario existe en tu MySQL local
        'password' => '',      // ⚠️ Asegúrate de que la contraseña sea correcta
    ],
    'production' => [
        'host' => 'localhost',
        'dbname' => 'u274409976_drive',
        'username' => 'u274409976_drive', // ⚠️ Tu usuario en producción
        'password' => 'Redes2804751$$$', // ⚠️ Clave en producción
    ]
];

// Obtener configuración actual
$dbConfig = $config[$environment];

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
