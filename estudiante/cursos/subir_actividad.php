<?php
session_start();
include '../../includes/conexion.php';

$stmt = $pdo->prepare("SELECT * FROM cursos");
$stmt->execute();
$cursos = $stmt->fetchAll();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_estudiante = $_SESSION['id_usuario'];
    $id_actividad = $_POST['id_actividad'];
    $archivo = $_FILES['archivo']['name'];
    $ruta = '../../archivos/' . $archivo;
    move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta);

    $sql = "INSERT INTO entregas (id_actividad, id_estudiante, archivo, fecha_entrega)
            VALUES (?, ?, ?, NOW())";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('iis', $id_actividad, $id_estudiante, $archivo);
    $stmt->execute();

    echo "Actividad subida exitosamente.";
}

?>

<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id_actividad" value="<?php echo $_GET['id_actividad']; ?>">
    <label>Seleccionar archivo:</label>
    <input type="file" name="archivo" required>
    <button type="submit">Subir</button>
</form>
