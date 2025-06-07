<?php
class Conexion {
    public static function conectar() {
        $link = 'mysql:host=localhost;dbname=gestion_notas;charset=utf8mb4';
        $usuario = 'root';
        $contrasenia = '';

        try {
            $pdo = new PDO($link, $usuario, $contrasenia);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (PDOException $e) {
            die("¡Error en la conexión!: " . $e->getMessage());
        }
    }
}
?>
