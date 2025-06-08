<?php
session_start();
include '../../includes/Conexion.php';
include '../../clases/curso.php';

$$stmt = $pdo->prepare("SELECT * FROM cursos");
$stmt->execute();
$cursos = $stmt->fetchAll();


$id_estudiante = $_SESSION['id_usuario'];

$sql = "SELECT c.id_curso, c.nombre FROM cursos c
        INNER JOIN inscripciones i ON c.id_curso = i.id_curso
        WHERE i.id_estudiante = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('i', $id_estudiante);
$stmt->execute();
$resultado = $stmt->get_result();

echo "<h2>Mis Cursos</h2>";
while ($fila = $resultado->fetch_assoc()) {
    echo "<p>{$fila['nombre']} <a href='ver_curso.php?id_curso={$fila['id_curso']}'>Ver</a></p>";
}
?>
