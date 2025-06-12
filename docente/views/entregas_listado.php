<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container mt-4">
  <h2>Listado de Entregas</h2>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Estudiante</th>
        <th>Actividad</th>
        <th>Archivo</th>
        <th>Fecha Entrega</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($entregas as $entrega): ?>
      <tr>
        <td><?= htmlspecialchars($entrega['estudiante']) ?></td>
        <td><?= htmlspecialchars($entrega['actividad']) ?></td>
        <td>
          <?php if (!empty($entrega['archivo'])): ?>
            <a href="../../uploads/<?= urlencode($entrega['archivo']) ?>" target="_blank">Descargar</a>
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($entrega['fecha_entrega']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
