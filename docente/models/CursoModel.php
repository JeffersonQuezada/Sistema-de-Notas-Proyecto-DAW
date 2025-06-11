<?php
require_once __DIR__ . '/../../includes/conexion.php';

class CursoModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function crearCurso($nombre, $descripcion, $codigo, $id_docente, $contrasena, $capacidad = 50, $grupo = null) {
        try {
            $sql = "INSERT INTO cursos (nombre_curso, descripcion, codigo, id_docente, contrasena, capacidad, grupo) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nombre, $descripcion, $codigo, $id_docente, $contrasena, $capacidad, $grupo]);
        } catch (PDOException $e) {
            error_log("Error al crear curso: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerCursoPorId($id_curso) {
        try {
            $sql = "SELECT c.*, u.nombre as nombre_docente 
                    FROM cursos c 
                    JOIN usuarios u ON c.id_docente = u.id_usuario 
                    WHERE c.id_curso = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_curso]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener curso: " . $e->getMessage());
            return false;
        }
    }

    public function listarCursos() {
        try {
            $sql = "SELECT c.*, u.nombre as nombre_docente 
                    FROM cursos c 
                    JOIN usuarios u ON c.id_docente = u.id_usuario 
                    ORDER BY c.nombre_curso";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar cursos: " . $e->getMessage());
            return [];
        }
    }

    public function listarCursosPorDocente($id_docente) {
        try {
            $sql = "SELECT c.*, 
                   COUNT(DISTINCT ec.id_estudiante) as total_estudiantes,
                   COUNT(DISTINCT a.id_actividad) as total_actividades
                   FROM cursos c 
                   LEFT JOIN estudiantes_cursos ec ON c.id_curso = ec.id_curso
                   LEFT JOIN actividades a ON c.id_curso = a.id_curso
                   WHERE c.id_docente = ?
                   GROUP BY c.id_curso";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_docente]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar cursos por docente: " . $e->getMessage());
            return [];
        }
    }

    public function listarCursosPorEstudiante($id_estudiante) {
        try {
            $sql = "SELECT c.*, u.nombre as nombre_docente
                    FROM cursos c
                    JOIN estudiantes_cursos ec ON c.id_curso = ec.id_curso
                    JOIN usuarios u ON c.id_docente = u.id_usuario
                    WHERE ec.id_estudiante = ?
                    ORDER BY c.nombre_curso";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_estudiante]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar cursos por estudiante: " . $e->getMessage());
            return [];
        }
    }

    public function actualizarCurso($id_curso, $nombre, $descripcion, $codigo, $capacidad, $grupo = null) {
        try {
            $sql = "UPDATE cursos 
                    SET nombre_curso = ?, descripcion = ?, codigo = ?, capacidad = ?, grupo = ?
                    WHERE id_curso = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nombre, $descripcion, $codigo, $capacidad, $grupo, $id_curso]);
        } catch (PDOException $e) {
            error_log("Error al actualizar curso: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarCurso($id_curso) {
        try {
            $sql = "DELETE FROM cursos WHERE id_curso = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id_curso]);
        } catch (PDOException $e) {
            error_log("Error al eliminar curso: " . $e->getMessage());
            return false;
        }
    }

    public function verificarCodigoExiste($codigo, $id_curso = null) {
        try {
            $sql = "SELECT COUNT(*) FROM cursos WHERE codigo = ?";
            $params = [$codigo];
            
            if ($id_curso) {
                $sql .= " AND id_curso != ?";
                $params[] = $id_curso;
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error al verificar código: " . $e->getMessage());
            return true;
        }
    }

    public function inscribirEstudiante($id_curso, $id_estudiante, $grupo = null) {
        try {
            $sql = "INSERT INTO estudiantes_cursos (id_curso, id_estudiante, grupo) VALUES (?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id_curso, $id_estudiante, $grupo]);
        } catch (PDOException $e) {
            error_log("Error al inscribir estudiante: " . $e->getMessage());
            return false;
        }
    }

    public function desinscribirEstudiante($id_curso, $id_estudiante) {
        try {
            $sql = "DELETE FROM estudiantes_cursos WHERE id_curso = ? AND id_estudiante = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id_curso, $id_estudiante]);
        } catch (PDOException $e) {
            error_log("Error al desinscribir estudiante: " . $e->getMessage());
            return false;
        }
    }

    public function verificarInscripcion($id_curso, $id_estudiante) {
        try {
            $sql = "SELECT COUNT(*) FROM estudiantes_cursos WHERE id_curso = ? AND id_estudiante = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_curso, $id_estudiante]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error al verificar inscripción: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerEstudiantesPorCurso($id_curso) {
        try {
            $sql = "SELECT u.id_usuario, u.nombre, u.correo, ec.grupo
                    FROM usuarios u
                    JOIN estudiantes_cursos ec ON u.id_usuario = ec.id_estudiante
                    WHERE ec.id_curso = ? AND u.rol = 'estudiante'
                    ORDER BY u.nombre";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_curso]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener estudiantes: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerEstadisticasCurso($id_curso) {
        try {
            $sql = "SELECT 
                    COUNT(DISTINCT ec.id_estudiante) as total_estudiantes,
                    COUNT(DISTINCT a.id_actividad) as total_actividades,
                    AVG(n.nota) as promedio_notas
                    FROM cursos c
                    LEFT JOIN estudiantes_cursos ec ON c.id_curso = ec.id_curso
                    LEFT JOIN actividades a ON c.id_curso = a.id_curso
                    LEFT JOIN notas n ON a.id_actividad = n.id_actividad
                    WHERE c.id_curso = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_curso]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener estadísticas: " . $e->getMessage());
            return false;
        }
    }

    public function verificarDocenteCurso($id_docente, $id_curso) {
        try {
            $sql = "SELECT COUNT(*) FROM cursos WHERE id_curso = ? AND id_docente = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_curso, $id_docente]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error al verificar docente-curso: " . $e->getMessage());
            return false;
        }
    }

    public function buscarCursos($termino) {
        try {
            $sql = "SELECT c.*, u.nombre as nombre_docente
                    FROM cursos c 
                    JOIN usuarios u ON c.id_docente = u.id_usuario 
                    WHERE c.nombre_curso LIKE ? OR c.codigo LIKE ? OR u.nombre LIKE ?
                    ORDER BY c.nombre_curso";
            $stmt = $this->pdo->prepare($sql);
            $terminoBusqueda = "%$termino%";
            $stmt->execute([$terminoBusqueda, $terminoBusqueda, $terminoBusqueda]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al buscar cursos: " . $e->getMessage());
            return [];
        }
    }
}
?>