<?php
$link = 'mysql:host=localhost;dbname=gestion_notas;charset=utf8mb4';
$usuario = 'root';
$contrasenia = '';

try {
    $pdo = new PDO($link, $usuario, $contrasenia);
    // Configurar el modo de error para obtener excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Establecer el modo de obtención predeterminado a objeto
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    //echo 'Conectado';
}
catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
    
}
?>

