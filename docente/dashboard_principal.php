<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Principal</title>
</head>
<body>
    <h2>Dashboard Principal</h2>

    <h3>Estudiantes en Riesgo</h3>
    <table border="1">
        <tr><th>Nombre</th><th>Entregas Pendientes</th></tr>
        <?php foreach ($estudiantesEnRiesgo as $estudiante): ?>
            <tr>
                <td><?php echo $estudiante['nombre']; ?></td>
                <td><?php echo $estudiante['entregas_pendientes']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3>Estadísticas de Cumplimiento</h3>
    <table border="1">
        <tr><th>Actividad</th><th>Entregas Recibidas</th><th>Estudiantes que Entregaron</th></tr>
        <?php foreach ($estadisticasCumplimiento as $estadistica): ?>
            <tr>
                <td><?php echo $estadistica['titulo']; ?></td>
                <td><?php echo $estadistica['entregas_recibidas']; ?></td>
                <td><?php echo $estadistica['estudiantes_entregaron']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>