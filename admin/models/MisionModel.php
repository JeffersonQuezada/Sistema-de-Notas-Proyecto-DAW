<?php
// filepath: admin/models/MisionModel.php
require_once __DIR__ . '/../../includes/conexion.php';

class MisionModel {
    private $pdo;
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    // Crear misión
    public function crearMision($titulo, $descripcion, $recompensa, $id_grupo) {
        $sql = "INSERT INTO misiones (titulo, descripcion, recompensa, id_grupo) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$titulo, $descripcion, $recompensa, $id_grupo]);
        return $this->pdo->lastInsertId();
    }

    // Listar misiones por grupo
    public function listarMisionesPorGrupo($id_grupo) {
        $sql = "SELECT * FROM misiones WHERE id_grupo = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_grupo]);
        return $stmt->fetchAll();
    }

    // Asignar misión a todos los estudiantes de un grupo
    public function asignarMisionAGrupo($id_mision, $id_grupo) {
        // Obtener estudiantes del grupo
        $sql = "SELECT id_usuario FROM estudiantes_grupos WHERE id_grupo = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_grupo]);
        $estudiantes = $stmt->fetchAll();

        foreach ($estudiantes as $est) {
            $sql2 = "INSERT IGNORE INTO misiones_estudiantes (id_mision, id_usuario, completado) VALUES (?, ?, 0)";
            $stmt2 = $this->pdo->prepare($sql2);
            $stmt2->execute([$id_mision, $est['id_usuario']]);
        }
    }
}
?>