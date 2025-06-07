<h2>Actividades</h2>
<a href="nueva_actividad.php">+ Nueva Actividad</a>
<table border="1">
    <tr>
        <th>Título</th>
        <th>Descripción</th>
        <th>Fecha de Entrega</th>
        <th>Grupo</th>
    </tr>
    <?php foreach($actividades as $actividad): ?>
    <tr>
        <td><?= $actividad['titulo'] ?></td>
        <td><?= $actividad['descripcion'] ?></td>
        <td><?= $actividad['fecha_entrega'] ?></td>
        <td><?= $actividad['id_grupo'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
