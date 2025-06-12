<?php
require_once __DIR__ . '/../../includes/conexion.php';

class DocenteModel {
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

    public function crearActividad($id_curso, $nombre, $descripcion, $fecha_limite, $tipo) {
        try {
            $sql = "INSERT INTO actividades (id_curso, nombre, descripcion, fecha_limite, tipo) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_curso, $nombre, $descripcion, $fecha_limite, $tipo]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al crear actividad: " . $e->getMessage());
            return false;
        }
    }

    public function editarActividad($id_actividad, $nombre, $descripcion, $fecha_limite, $tipo) {
        try {
            $sql = "UPDATE actividades SET nombre = ?, descripcion = ?, fecha_limite = ?, tipo = ?
                    WHERE id_actividad = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nombre, $descripcion, $fecha_limite, $tipo, $id_actividad]);
        } catch (PDOException $e) {
            error_log("Error al editar actividad: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerActividadPorId($id_actividad) {
        try {
            $sql = "SELECT * FROM actividades WHERE id_actividad = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_actividad]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener actividad: " . $e->getMessage());
            return false;
        }
    }

    public function calificarEntrega($id_estudiante, $id_curso, $id_actividad, $nota, $observaciones) {
        try {
            // Verificar si ya existe una nota para esta actividad
            $sql_check = "SELECT id_nota FROM notas 
                          WHERE id_estudiante = ? AND id_curso = ? AND id_actividad = ?";
            $stmt_check = $this->pdo->prepare($sql_check);
            $stmt_check->execute([$id_estudiante, $id_curso, $id_actividad]);
            $nota_existente = $stmt_check->fetch();

            if ($nota_existente) {
                // Actualizar nota existente
                $sql = "UPDATE notas SET nota = ?, observaciones = ? 
                        WHERE id_nota = ?";
                $stmt = $this->pdo->prepare($sql);
                return $stmt->execute([$nota, $observaciones, $nota_existente['id_nota']]);
            } else {
                // Crear nueva nota
                $sql = "INSERT INTO notas (id_estudiante, id_curso, id_actividad, nota, observaciones)
                        VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->pdo->prepare($sql);
                return $stmt->execute([$id_estudiante, $id_curso, $id_actividad, $nota, $observaciones]);
            }
        } catch (PDOException $e) {
            error_log("Error al calificar entrega: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerEstadisticasCurso($id_curso) {
        try {
            $sql = "SELECT 
                    COUNT(DISTINCT ec.id_estudiante) as total_estudiantes,
                    COUNT(DISTINCT a.id_actividad) as total_actividades,
                    AVG(n.nota) as promedio_curso,
                    MAX(n.nota) as nota_maxima,
                    MIN(n.nota) as nota_minima
                    FROM cursos c
                    LEFT JOIN estudiantes_cursos ec ON c.id_curso = ec.id_curso
                    LEFT JOIN actividades a ON c.id_curso = a.id_curso
                    LEFT JOIN notas n ON c.id_curso = n.id_curso
                    WHERE c.id_curso = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_curso]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener estadísticas: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerEstudiantesEnRiesgo($id_curso) {
        try {
            $sql = "SELECT u.id_usuario, u.nombre, AVG(n.nota) as promedio
                    FROM usuarios u
                    JOIN estudiantes_cursos ec ON u.id_usuario = ec.id_estudiante
                    JOIN notas n ON u.id_usuario = n.id_estudiante AND ec.id_curso = n.id_curso
                    WHERE ec.id_curso = ? AND u.rol = 'estudiante'
                    GROUP BY u.id_usuario
                    HAVING promedio < 60";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_curso]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener estudiantes en riesgo: " . $e->getMessage());
            return [];
        }
    }

    public function crearMision($titulo, $descripcion, $recompensa, $id_grupo = null) {
        try {
            $sql = "INSERT INTO misiones (titulo, descripcion, recompensa, id_grupo)
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$titulo, $descripcion, $recompensa, $id_grupo]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al crear misión: " . $e->getMessage());
            return false;
        }
    }

public function listarGruposPorDocente($id_docente) {
    try {
        $sql = "SELECT * FROM grupos WHERE id_docente = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_docente]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error al listar grupos por docente: " . $e->getMessage());
        return false;
    }
}}
?>