<?php
require_once __DIR__ . '/../../includes/conexion.php';

class EstudianteModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function asignarEstudianteAGrupo($id_estudiante, $id_grupo) {
        try {
            // Verificar que el usuario es estudiante
            $sql_check = "SELECT COUNT(*) FROM usuarios WHERE id_usuario = ? AND rol = 'estudiante'";
            $stmt_check = $this->pdo->prepare($sql_check);
            $stmt_check->execute([$id_estudiante]);
            
            if ($stmt_check->fetchColumn() == 0) {
                return false;
            }

            // Verificar si ya está asignado
            $sql_check = "SELECT COUNT(*) FROM estudiantes_grupos WHERE id_usuario = ? AND id_grupo = ?";
            $stmt_check = $this->pdo->prepare($sql_check);
            $stmt_check->execute([$id_estudiante, $id_grupo]);
            
            if ($stmt_check->fetchColumn() > 0) {
                return true; // Ya está asignado
            }

            $sql = "INSERT INTO estudiantes_grupos (id_usuario, id_grupo) VALUES (?, ?)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id_estudiante, $id_grupo]);
        } catch (PDOException $e) {
            error_log("Error en asignarEstudianteAGrupo: " . $e->getMessage());
            return false;
        }
    }

    public function listarEstudiantesPorGrupo($id_grupo) {
        try {
            $sql = "SELECT u.id_usuario, u.nombre, u.correo, u.rol 
                    FROM usuarios u
                    JOIN estudiantes_grupos eg ON u.id_usuario = eg.id_usuario
                    WHERE eg.id_grupo = ? AND u.rol = 'estudiante'";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_grupo]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en listarEstudiantesPorGrupo: " . $e->getMessage());
            return [];
        }
    }

    public function verificarEstudianteEnGrupo($id_estudiante, $id_grupo) {
        try {
            $sql = "SELECT COUNT(*) FROM estudiantes_grupos 
                    WHERE id_usuario = ? AND id_grupo = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_estudiante, $id_grupo]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error en verificarEstudianteEnGrupo: " . $e->getMessage());
            return false;
        }
    }
}
?>