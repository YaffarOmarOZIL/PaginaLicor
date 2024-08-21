<?php
$servername = "sql110.infinityfree.com"; // Host de la base de datos
$username = "if0_37149359"; // Nombre de usuario de MySQL
$password = "zj0q1f9TlUI"; // Contraseña de MySQL
$dbname = "if0_37149359_base"; // Nombre de la base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
