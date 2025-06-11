<?php
require_once __DIR__ . '/../../includes/conexion.php';

class ActividadModel{
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

public function crearActividad($nombre, $descripcion, $fecha_limite, $id_curso, $tipo = 'Tarea') {
    $tiposPermitidos = ['Tarea', 'Examen', 'Proyecto'];
    if (!in_array($tipo, $tiposPermitidos)) {
        throw new Exception("Tipo de actividad no válido");
    }
    
    $sql = "INSERT INTO actividades (id_curso, nombre, descripcion, fecha_limite, tipo) 
            VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_curso, $nombre, $descripcion, $fecha_limite, $tipo]);
    }

    public function editarActividad($id_actividad, $nombre, $descripcion, $fecha_limite, $tipo) {
        $sql = "UPDATE actividades SET nombre = ?, descripcion = ?, fecha_limite = ?, tipo = ? WHERE id_actividad = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nombre, $descripcion, $fecha_limite, $tipo, $id_actividad]);
    }

    public function eliminarActividad($id_actividad) {
        $sql = "DELETE FROM actividades WHERE id_actividad = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_actividad]);
    }

    public function listarActividadesPorCurso($id_curso) {
        $sql = "SELECT * FROM actividades WHERE id_curso = ? ORDER BY fecha_limite DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_curso]);
        return $stmt->fetchAll();
    }

    public function obtenerActividadPorId($id_actividad) {
        $sql = "SELECT * FROM actividades WHERE id_actividad = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_actividad]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>