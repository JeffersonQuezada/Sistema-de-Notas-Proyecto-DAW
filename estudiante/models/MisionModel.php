<?php
require_once __DIR__ . '/../../includes/conexion.php';

class MisionModel {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    public function listarMisionesDisponibles($id_usuario) {
        $sql = "SELECT m.*, 
                       me.completado,
                       CASE WHEN me.id_mision IS NOT NULL THEN 1 ELSE 0 END AS aceptada
                FROM misiones m
                LEFT JOIN misiones_estudiantes me ON m.id_mision = me.id_mision AND me.id_usuario = ?
                ORDER BY m.titulo";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function aceptarMision($id_usuario, $id_mision) {
        $this->pdo->beginTransaction();
        
        try {
            // Verificar si la misión existe y está disponible
            $sql = "SELECT id_mision FROM misiones 
                    WHERE id_mision = ? 
                    AND fecha_inicio <= NOW() 
                    AND (fecha_fin IS NULL OR fecha_fin >= NOW())";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_mision]);
            
            if (!$stmt->fetch()) {
                throw new Exception("Misión no disponible");
            }
            
            // Insertar o actualizar estado
            $sql = "INSERT INTO misiones_estudiantes (id_usuario, id_mision, completado) VALUES (?, ?, 0)
                    ON DUPLICATE KEY UPDATE estado = 'aceptada', fecha_aceptacion = NOW()";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_usuario, $id_mision]);
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    
    public function obtenerMisionesActivasPorEstudiante($id_usuario) {
        $sql = "SELECT m.*, me.fecha_aceptacion
                FROM misiones m
                JOIN misiones_estudiantes me ON m.id_mision = me.id_mision
                WHERE me.id_usuario = ? 
                AND me.completado = 'aceptada'
                AND (m.fecha_fin IS NULL OR m.fecha_fin >= NOW())";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function verificarCompletarMisiones($id_usuario) {
        // Implementar lógica para verificar si se completaron misiones
        // basado en actividades, entregas, calificaciones, etc.
        // Retornar array de misiones completadas
        return [];
    }
}
?>