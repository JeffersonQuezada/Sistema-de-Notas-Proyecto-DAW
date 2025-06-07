<?php
session_start();
include '../../includes/conexion.php';

$stmt = $pdo->prepare("SELECT * FROM cursos");
$stmt->execute();
$cursos = $stmt->fetchAll();


$id_estudiante = $_SESSION['id_usuario'];

$sql = "SELECT c.nombre AS curso, a.titulo AS actividad, e.nota
        FROM entregas e
        INNER JOIN actividades a ON e.id_actividad = a.id_actividad
        INNER JOIN cursos c ON a.id_curso = c.id_curso
        WHERE e.id_estudiante = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('i', $id_estudiante);
$stmt->execute();
$resultado = $stmt->get_result();

echo "<h2>Mis Notas</h2>";
while ($fila = $resultado->fetch_assoc()) {
    echo "<p>Curso: {$fila['curso']} | Actividad: {$fila['actividad']} | Nota: {$fila['nota']}</p>";
}

$sql_prom = "SELECT c.nombre AS curso, AVG(e.nota) AS promedio
             FROM entregas e
             INNER JOIN actividades a ON e.id_actividad = a.id_actividad
             INNER JOIN cursos c ON a.id_curso = c.id_curso
             WHERE e.id_estudiante = ?
             GROUP BY c.id_curso";
$stmt_prom = $conexion->prepare($sql_prom);
$stmt_prom->bind_param('i', $id_estudiante);
$stmt_prom->execute();
$resultado_prom = $stmt_prom->get_result();

echo "<h2>Promedio por Curso</h2>";
while ($fila = $resultado_prom->fetch_assoc()) {
    echo "<p>Curso: {$fila['curso']} | Promedio: " . number_format($fila['promedio'], 2) . "</p>";
}
?>
