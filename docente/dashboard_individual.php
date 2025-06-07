<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Progreso Individual</title>
</head>
<body>
    <h2>Progreso Individual</h2>

    <table border="1">
        <tr><th>Actividad</th><th>Calificación</th><th>Comentario</th><th>Fecha de Entrega</th></tr>
        <?php foreach ($progresoIndividual as $entrega): ?>
            <tr>
                <td><?php echo $entrega['titulo']; ?></td>
                <td><?php echo $entrega['calificacion']; ?></td>
                <td><?php echo $entrega['comentario']; ?></td>
                <td><?php echo $entrega['fecha_entrega']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>