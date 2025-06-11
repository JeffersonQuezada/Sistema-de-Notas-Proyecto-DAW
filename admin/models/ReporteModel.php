<?php
require_once __DIR__ . '/../../includes/conexion.php';
class ReporteModel {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    public function obtenerDatosUsuarios() {
        $stmt = $this->pdo->query("
            SELECT rol, COUNT(*) as total 
            FROM usuarios 
            GROUP BY rol
        ");
        $usuariosPorRol = $stmt->fetchAll();
        
        $stmt = $this->pdo->query("
            SELECT nombre, correo, fecha_registro 
            FROM usuarios 
            ORDER BY fecha_registro DESC 
            LIMIT 5
        ");
        $ultimosUsuarios = $stmt->fetchAll();
        
        return [
            'usuariosPorRol' => $usuariosPorRol,
            'ultimosUsuarios' => $ultimosUsuarios
        ];
    }
    
    public function obtenerDatosCursos() {
        $stmt = $this->pdo->query("
            SELECT u.nombre as docente, COUNT(*) as total 
            FROM cursos c
            JOIN usuarios u ON c.id_docente = u.id_usuario
            GROUP BY c.id_docente
        ");
        $cursosPorDocente = $stmt->fetchAll();
        
        $stmt = $this->pdo->query("
            SELECT c.nombre_curso, COUNT(ec.id_estudiante) as total_estudiantes
            FROM cursos c
            LEFT JOIN estudiantes_cursos ec ON c.id_curso = ec.id_curso
            GROUP BY c.id_curso
            ORDER BY total_estudiantes DESC
            LIMIT 5
        ");
        $cursosMasPopulares = $stmt->fetchAll();
        
        return [
            'cursosPorDocente' => $cursosPorDocente,
            'cursosMasPopulares' => $cursosMasPopulares
        ];
    }
    
    public function obtenerDatosActividades() {
        $stmt = $this->pdo->query("
            SELECT tipo, COUNT(*) as total 
            FROM actividades 
            GROUP BY tipo
        ");
        $actividadesPorTipo = $stmt->fetchAll();
        
        $stmt = $this->pdo->query("
            SELECT a.nombre, c.nombre_curso, a.fecha_limite 
            FROM actividades a
            JOIN cursos c ON a.id_curso = c.id_curso
            WHERE a.fecha_limite > NOW()
            ORDER BY a.fecha_limite ASC
            LIMIT 5
        ");
        $actividadesProximas = $stmt->fetchAll();
        
        return [
            'actividadesPorTipo' => $actividadesPorTipo,
            'actividadesProximas' => $actividadesProximas
        ];
    }
}