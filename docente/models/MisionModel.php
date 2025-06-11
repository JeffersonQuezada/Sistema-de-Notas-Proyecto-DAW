<?php
require_once __DIR__ . '/../../includes/conexion.php';

class MisionModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function crearMision($titulo, $descripcion, $recompensa, $fecha_fin = null, $id_grupo = null) {
        try {
            $sql = "INSERT INTO misiones (titulo, descripcion, recompensa, fecha_fin, id_grupo) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$titulo, $descripcion, $recompensa, $fecha_fin, $id_grupo]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al crear misión: " . $e->getMessage());
            return false;
        }
    }

    public function aceptarMision($id_mision, $id_estudiante) {
        try {
            // Verificar si la misión ya fue aceptada
            $sql_check = "SELECT COUNT(*) FROM misiones_estudiantes 
                          WHERE id_usuario = ? AND id_mision = ?";
            $stmt_check = $this->pdo->prepare($sql_check);
            $stmt_check->execute([$id_estudiante, $id_mision]);
            
            if ($stmt_check->fetchColumn() > 0) {
                return true; // Ya está aceptada
            }

            $sql = "INSERT INTO misiones_estudiantes (id_mision, id_usuario, fecha_aceptacion) 
                    VALUES (?, ?, NOW())";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id_mision, $id_estudiante]);
        } catch (PDOException $e) {
            error_log("Error al aceptar misión: " . $e->getMessage());
            return false;
        }
    }

    public function finalizarMision($id_mision, $id_estudiante) {
        try {
            $sql = "UPDATE misiones_estudiantes 
                    SET completado = 1, fecha_finalizacion = NOW() 
                    WHERE id_usuario = ? AND id_mision = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id_estudiante, $id_mision]);
        } catch (PDOException $e) {
            error_log("Error al finalizar misión: " . $e->getMessage());
            return false;
        }
    }

    public function listarMisiones($id_grupo = null) {
        try {
            $sql = "SELECT m.*, g.nombre as nombre_grupo
                   FROM misiones m
                   LEFT JOIN grupos g ON m.id_grupo = g.id_grupo
                   WHERE m.id_grupo IS NULL OR m.id_grupo = ?
                   ORDER BY m.fecha_inicio DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_grupo]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar misiones: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerMisionPorId($id_mision) {
        try {
            $sql = "SELECT m.*, g.nombre as nombre_grupo
                   FROM misiones m
                   LEFT JOIN grupos g ON m.id_grupo = g.id_grupo
                   WHERE m.id_mision = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_mision]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener misión: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerMisionesEstudiante($id_estudiante) {
        try {
            $sql = "SELECT m.*, me.completado, me.fecha_aceptacion,
                   g.nombre as nombre_grupo
                   FROM misiones m
                   LEFT JOIN grupos g ON m.id_grupo = g.id_grupo
                   LEFT JOIN misiones_estudiantes me ON m.id_mision = me.id_mision AND me.id_usuario = ?
                   WHERE m.id_grupo IS NULL OR 
                         m.id_grupo IN (SELECT id_grupo FROM estudiantes_grupos WHERE id_usuario = ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_estudiante, $id_estudiante]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener misiones del estudiante: " . $e->getMessage());
            return [];
        }
    }
}
?>