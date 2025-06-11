<?php
require_once __DIR__ . '/../../includes/conexion.php';

class GrupoModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function crearGrupo($nombre, $id_docente) {
        try {
            $sql = "INSERT INTO grupos (nombre, id_docente) VALUES (?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$nombre, $id_docente]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al crear grupo: " . $e->getMessage());
            return false;
        }
    }

    public function editarGrupo($id_grupo, $nombre, $id_docente) {
        try {
            $sql = "UPDATE grupos SET nombre = ? WHERE id_grupo = ? AND id_docente = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nombre, $id_grupo, $id_docente]);
        } catch (PDOException $e) {
            error_log("Error al editar grupo: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarGrupo($id_grupo, $id_docente) {
        try {
            $sql = "DELETE FROM grupos WHERE id_grupo = ? AND id_docente = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id_grupo, $id_docente]);
        } catch (PDOException $e) {
            error_log("Error al eliminar grupo: " . $e->getMessage());
            return false;
        }
    }

    public function asignarEstudianteGrupo($id_usuario, $id_grupo) {
        try {
            // First check if the student is already in the group
            $sql = "SELECT * FROM usuario_grupo WHERE id_usuario = ? AND id_grupo = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_usuario, $id_grupo]);
            
            if ($stmt->rowCount() > 0) {
                return true; // Already assigned
            }

            $sql = "INSERT INTO usuario_grupo (id_usuario, id_grupo) VALUES (?, ?)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id_usuario, $id_grupo]);
        } catch (PDOException $e) {
            error_log("Error al asignar estudiante a grupo: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerEstudiantesPorGrupo($id_grupo) {
        try {
            $sql = "SELECT u.* FROM usuarios u
                    JOIN usuario_grupo ug ON u.id_usuario = ug.id_usuario
                    WHERE ug.id_grupo = ? AND u.rol = 'estudiante'";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_grupo]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener estudiantes por grupo: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerGrupoPorId($id_grupo) {
        try {
            $sql = "SELECT * FROM grupos WHERE id_grupo = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_grupo]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener grupo por ID: " . $e->getMessage());
            return false;
        }
    }
}
?>