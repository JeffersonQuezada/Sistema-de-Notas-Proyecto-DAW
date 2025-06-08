<?php
session_start();
include '../../includes/conexion.php';

$$stmt = $pdo->prepare("SELECT * FROM cursos");
$stmt->execute();
$cursos = $stmt->fetchAll();

$id_estudiante = $_SESSION['id_usuario'];
$id_curso = $_GET['id_curso'];

$sql = "INSERT INTO inscripciones (id_estudiante, id_curso) VALUES (?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('ii', $id_estudiante, $id_curso);
if ($stmt->execute()) {
    echo "Inscripción exitosa.";
} else {
    echo "Error al inscribirse.";
}
?>
<a href='cursos_disponibles.php'>Volver</a>
