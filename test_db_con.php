<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cambiar "localhost" si es necesario
$host = "198.59.144.155"; // prueba también 127.0.0.1
$user = "datallizer_lysce";
$pass = "P_LySgRy123$&AVOM";
$dbname = "datallizer_lysce";

echo "Intentando conectar...<br>";

$con = mysqli_connect($host, $user, $pass, $dbname);

if (!$con) {
    die('❌ Error de conexión: ' . mysqli_connect_error());
}

echo "✅ ¡Conexión exitosa!<br>";

mysqli_close($con);
