<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Progreso Grupal</title>
</head>
<body>
    <h2>Progreso Grupal</h2>

    <table border="1">
        <tr><th>Estudiante</th><th>Actividad</th><th>Calificación</th><th>Comentario</th><th>Fecha de Entrega</th></tr>
        <?php foreach ($progresoGrupal as $entrega): ?>
            <tr>
                <td><?php echo $entrega['nombre']; ?></td>
                <td><?php echo $entrega['titulo']; ?></td>
                <td><?php echo $entrega['calificacion']; ?></td>
                <td><?php echo $entrega['comentario']; ?></td>
                <td><?php echo $entrega['fecha_entrega']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>