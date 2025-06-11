<?php
require_once __DIR__ . '/../../includes/conexion.php';

class GrupoModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function crearGrupo($nombre, $id_usuario) {
        $sql = "INSERT INTO grupos (nombre, id_usuario) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nombre, $id_usuario]);
    }

    public function editarGrupo($id_grupo, $nombre, $id_usuario) {
        $sql = "UPDATE grupos SET nombre = ? WHERE id_grupo = ? AND id_usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nombre, $id_grupo, $id_usuario]);
    }

    public function eliminarGrupo($id_grupo, $id_usuario) {
        $sql = "DELETE FROM grupos WHERE id_grupo = ? AND id_usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_grupo, $id_usuario]);
    }

    public function asignarEstudianteGrupo($id_usuario, $id_grupo) {
        $sql = "INSERT INTO estudiantes_grupos (id_usuario, id_grupo) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_usuario, $id_grupo]);
    }

    public function obtenerEstudiantesPorGrupo($id_grupo) {
        $sql = "SELECT u.* FROM usuarios u
                INNER JOIN estudiantes_grupos eg ON u.id_usuario = eg.id_usuario
                WHERE eg.id_grupo = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_grupo]);
        return $stmt->fetchAll();
    }

    public function listarGrupos($id_usuario) {
        $sql = "SELECT * FROM grupos WHERE id_usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll();
    }
}
?>