
<?php
require_once __DIR__ . '/../../includes/conexion.php';

class EstudianteModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function asignarEstudianteAGrupo($id_estudiante, $id_grupo) {
        $sql = "INSERT INTO estudiantes_grupos (id_usuario, id_grupo) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_estudiante, $id_grupo]);
    }

    public function listarEstudiantesPorGrupo($id_grupo) {
        $sql = "SELECT u.* FROM usuarios u
                JOIN estudiantes_grupos eg ON u.id_usuario = eg.id_usuario
                WHERE eg.id_grupo = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_grupo]);
        return $stmt->fetchAll();
    }
}
?>