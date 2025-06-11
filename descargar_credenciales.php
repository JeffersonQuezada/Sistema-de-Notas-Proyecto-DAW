<?php
session_start();
if (!isset($_SESSION['credenciales_descarga'])) {
    header("Location: crear_cuenta.php");
    exit();
}

$correo = $_SESSION['credenciales_descarga']['correo'];
$contrasena = $_SESSION['credenciales_descarga']['contrasena'];

header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="credenciales.txt"');

echo "Correo: $correo\nContraseña: $contrasena\n";
unset($_SESSION['credenciales_descarga']); // Solo permite una descarga
exit();
?>