<?php
require_once __DIR__ . '/../../includes/conexion.php';

class DashboardModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function obtenerProgresoIndividual($id_estudiante) {
        $sql = "SELECT a.nombre, e.calificacion, e.comentario, e.fecha_entrega
                FROM entregas e
                JOIN actividades a ON e.id_actividad = a.id_actividad
                WHERE e.id_estudiante = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchAll();
    }

    public function obtenerProgresoGrupal($id_grupo) {
        $sql = "SELECT u.nombre, a.nombre as actividad, e.calificacion, e.comentario, e.fecha_entrega
                FROM entregas e
                JOIN usuarios u ON e.id_estudiante = u.id_usuario
                JOIN actividades a ON e.id_actividad = a.id_actividad
                JOIN estudiantes_grupos eg ON u.id_usuario = eg.id_usuario
                WHERE eg.id_grupo = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_grupo]);
        return $stmt->fetchAll();
    }

  public function identificarEstudiantesEnRiesgo() {
    $sql = "SELECT u.nombre, COUNT(a.id_actividad) - COUNT(e.id_entrega) as entregas_pendientes
            FROM usuarios u
            JOIN estudiantes_grupos eg ON u.id_usuario = eg.id_usuario
            JOIN actividades a ON a.id_curso = (
                SELECT id_curso FROM cursos WHERE id_curso IN (
                    SELECT id_curso FROM estudiantes_cursos WHERE id_estudiante = u.id_usuario
                )
            )
            LEFT JOIN entregas e ON u.id_usuario = e.id_estudiante AND a.id_actividad = e.id_actividad
            WHERE u.rol = 'estudiante'
            GROUP BY u.id_usuario
            HAVING entregas_pendientes > 0";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

    public function obtenerEstadisticasCumplimiento() {
        $sql = "SELECT a.nombre, 
                       COUNT(e.id_entrega) as entregas_recibidas, 
                       COUNT(DISTINCT e.id_estudiante) as estudiantes_entregaron
                FROM actividades a
                LEFT JOIN entregas e ON a.id_actividad = e.id_actividad
                GROUP BY a.id_actividad";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>