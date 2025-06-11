<?php
require_once __DIR__ . '/../../includes/conexion.php';

class AdminModel {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    public function obtenerEstadisticas() {
        $estadisticas = [
            'total_usuarios' => 0,
            'total_cursos' => 0,
            'total_actividades' => 0,
            'total_entregas' => 0
        ];
        
        // Obtener total de usuarios
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM usuarios");
        $estadisticas['total_usuarios'] = $stmt->fetch()['total'];
        
        // Obtener total de cursos
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM cursos");
        $estadisticas['total_cursos'] = $stmt->fetch()['total'];
        
        // Obtener total de actividades
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM actividades");
        $estadisticas['total_actividades'] = $stmt->fetch()['total'];
        
        // Obtener total de entregas
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM entregas");
        $estadisticas['total_entregas'] = $stmt->fetch()['total'];
        
        return $estadisticas;
    }
}