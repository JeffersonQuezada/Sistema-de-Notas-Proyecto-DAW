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
    
    public function obtenerEstadisticasDashboard() {
        $estadisticas = [];

        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM usuarios");
        $estadisticas['total_usuarios'] = $stmt->fetch()['total'];

        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM cursos");
        $estadisticas['total_cursos'] = $stmt->fetch()['total'];

        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM usuarios WHERE rol = 'docente'");
        $estadisticas['total_docentes'] = $stmt->fetch()['total'];

        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM actividades");
        $estadisticas['total_actividades'] = $stmt->fetch()['total'];

        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM entregas");
        $estadisticas['total_entregas'] = $stmt->fetch()['total'];

        return $estadisticas;
    }
    
    // Docentes con mayor % de cumplimiento de tareas
    public function docentesCumplimientoTareas($limite = 5) {
        // ¡OJO! No uses LIMIT ? con execute, sino interpolación directa (castea a int para seguridad)
        $limite = (int)$limite;
        $sql = "
            SELECT u.nombre AS docente, 
                   ROUND(AVG(
                       (SELECT COUNT(*) FROM entregas e 
                        JOIN actividades a2 ON e.id_actividad = a2.id_actividad 
                        WHERE a2.id_curso = c.id_curso
                       ) / 
                       (SELECT COUNT(*) FROM actividades a WHERE a.id_curso = c.id_curso)
                   ) * 100, 2) AS porcentaje_cumplimiento
            FROM cursos c
            JOIN usuarios u ON c.id_docente = u.id_usuario
            GROUP BY c.id_docente
            ORDER BY porcentaje_cumplimiento DESC
            LIMIT $limite
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function obtenerTodosLosCursos() {
        $stmt = $this->pdo->query("SELECT id_curso, nombre_curso FROM cursos ORDER BY nombre_curso");
        return $stmt->fetchAll();
    }

    // Alumnos con mejores notas por curso
    public function mejoresAlumnosPorCurso($limite = 5, $id_curso = '') {
        $params = [];
        $where = '';
        if ($id_curso) {
            $where = 'WHERE n.id_curso = ?';
            $params[] = $id_curso;
        }
        $sql = "
            SELECT c.nombre_curso, u.nombre AS alumno, AVG(n.nota) AS promedio
            FROM notas n
            JOIN usuarios u ON n.id_estudiante = u.id_usuario
            JOIN cursos c ON n.id_curso = c.id_curso
            $where
            GROUP BY n.id_curso, n.id_estudiante
            ORDER BY c.id_curso, promedio DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $resultados = [];
        foreach ($stmt->fetchAll() as $row) {
            $curso = $row['nombre_curso'];
            if (!isset($resultados[$curso])) $resultados[$curso] = [];
            if (count($resultados[$curso]) < $limite) {
                $resultados[$curso][] = [
                    'alumno' => $row['alumno'],
                    'promedio' => round($row['promedio'], 2)
                ];
            }
        }
        return $resultados;
    }
}