<?php
require_once __DIR__ . '/../../includes/conexion.php';

class HorarioModel {
    private $pdo;
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    public function listarHorariosPorCurso($id_curso) {
        $sql = "SELECT * FROM horarios WHERE id_curso = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_curso]);
        return $stmt->fetchAll();
    }
    public function crearHorario($id_curso, $dia, $hora_inicio, $hora_fin) {
        $sql = "INSERT INTO horarios (id_curso, dia, hora_inicio, hora_fin) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_curso, $dia, $hora_inicio, $hora_fin]);
    }
    public function eliminarHorario($id_horario) {
        $sql = "DELETE FROM horarios WHERE id_horario = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_horario]);
    }
}
?>

<?php
// filepath: admin/views/logs_listado.php
include __DIR__ . '/../../includes/header.php';
$logs = $logs ?? [];
?>

<div class="container mt-4">
    <h2>Historial de Acciones (Logs)</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Acción</th>
                <th>Detalle</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($logs) > 0): ?>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= htmlspecialchars($log['fecha']) ?></td>
                        <td><?= htmlspecialchars($log['nombre'] ?? 'Desconocido') ?></td>
                        <td><?= htmlspecialchars($log['accion']) ?></td>
                        <td><?= htmlspecialchars($log['detalle']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center text-muted">No hay registros de logs.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>