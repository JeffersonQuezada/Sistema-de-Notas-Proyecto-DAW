<?php
require_once '../includes/Database.php';

class DocenteModel {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    // ============================================
    // 1. GESTIÓN DE GRUPOS Y ESTUDIANTES
    // ============================================

    /**
     * Crear un nuevo grupo
     */
    public function crearGrupo($nombre, $id_docente) {
        try {
            $sql = "INSERT INTO grupos (nombre, id_docente) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$nombre, $id_docente]);
            
            if ($result) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            throw new Exception("Error al crear grupo: " . $e->getMessage());
        }
    }

    /**
     * Editar un grupo existente
     */
    public function editarGrupo($id_grupo, $nombre, $id_docente) {
        try {
            $sql = "UPDATE grupos SET nombre = ? WHERE id_grupo = ? AND id_docente = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nombre, $id_grupo, $id_docente]);
        } catch (PDOException $e) {
            throw new Exception("Error al editar grupo: " . $e->getMessage());
        }
    }

    /**
     * Eliminar un grupo
     */
    public function eliminarGrupo($id_grupo, $id_docente) {
        try {
            // Primero eliminar estudiantes del grupo
            $this->db->beginTransaction();
            
            $sql1 = "DELETE FROM estudiantes_grupos WHERE id_grupo = ?";
            $stmt1 = $this->db->prepare($sql1);
            $stmt1->execute([$id_grupo]);
            
            $sql2 = "DELETE FROM grupos WHERE id_grupo = ? AND id_docente = ?";
            $stmt2 = $this->db->prepare($sql2);
            $result = $stmt2->execute([$id_grupo, $id_docente]);
            
            $this->db->commit();
            return $result;
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new Exception("Error al eliminar grupo: " . $e->getMessage());
        }
    }

    /**
     * Asignar estudiante a grupo
     */
    public function asignarEstudianteGrupo($id_usuario, $id_grupo) {
        try {
            $sql = "INSERT INTO estudiantes_grupos (id_usuario, id_grupo) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id_usuario, $id_grupo]);
        } catch (PDOException $e) {
            throw new Exception("Error al asignar estudiante: " . $e->getMessage());
        }
    }

    /**
     * Obtener estudiantes por grupo
     */
    public function obtenerEstudiantesPorGrupo($id_grupo) {
        try {
            $sql = "SELECT u.id_usuario, u.nombre, u.correo 
                    FROM usuarios u 
                    INNER JOIN estudiantes_grupos eg ON u.id_usuario = eg.id_usuario 
                    WHERE eg.id_grupo = ? AND u.rol = 'estudiante'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_grupo]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Error al obtener estudiantes: " . $e->getMessage());
        }
    }

    /**
     * Obtener todos los grupos de un docente
     */
    public function obtenerGruposDocente($id_docente) {
        try {
            $sql = "SELECT * FROM grupos WHERE id_docente = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_docente]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Error al obtener grupos: " . $e->getMessage());
        }
    }

    // ============================================
    // 2. PUBLICACIÓN DE ACTIVIDADES
    // ============================================

    /**
     * Crear nueva actividad
     */
    public function crearActividad($id_curso, $nombre, $descripcion, $fecha_limite, $tipo) {
        try {
            $sql = "INSERT INTO actividades (id_curso, nombre, descripcion, fecha_limite, tipo) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$id_curso, $nombre, $descripcion, $fecha_limite, $tipo]);
            
            if ($result) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            throw new Exception("Error al crear actividad: " . $e->getMessage());
        }
    }

    /**
     * Editar actividad existente
     */
    public function editarActividad($id_actividad, $nombre, $descripcion, $fecha_limite, $tipo) {
        try {
            $sql = "UPDATE actividades SET nombre = ?, descripcion = ?, fecha_limite = ?, tipo = ? 
                    WHERE id_actividad = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nombre, $descripcion, $fecha_limite, $tipo, $id_actividad]);
        } catch (PDOException $e) {
            throw new Exception("Error al editar actividad: " . $e->getMessage());
        }
    }

    /**
     * Eliminar actividad
     */
    public function eliminarActividad($id_actividad) {
        try {
            $sql = "DELETE FROM actividades WHERE id_actividad = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id_actividad]);
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar actividad: " . $e->getMessage());
        }
    }

    /**
     * Obtener actividades por curso
     */
    public function obtenerActividadesPorCurso($id_curso) {
        try {
            $sql = "SELECT * FROM actividades WHERE id_curso = ? ORDER BY fecha_limite DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_curso]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Error al obtener actividades: " . $e->getMessage());
        }
    }

    // ============================================
    // 3. REVISIÓN DE ENTREGAS
    // ============================================

    /**
     * Obtener entregas por actividad
     */
    public function obtenerEntregasPorActividad($id_actividad) {
        try {
            $sql = "SELECT e.*, u.nombre as nombre_estudiante, u.correo 
                    FROM entregas e 
                    INNER JOIN usuarios u ON e.id_estudiante = u.id_usuario 
                    WHERE e.id_actividad = ? 
                    ORDER BY e.fecha_entrega DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_actividad]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Error al obtener entregas: " . $e->getMessage());
        }
    }

    /**
     * Calificar entrega
     */
    public function calificarEntrega($id_estudiante, $id_curso, $id_actividad, $nota, $observaciones) {
        try {
            // Verificar si ya existe una calificación
            $sql_check = "SELECT id_nota FROM notas WHERE id_estudiante = ? AND id_actividad = ?";
            $stmt_check = $this->db->prepare($sql_check);
            $stmt_check->execute([$id_estudiante, $id_actividad]);
            
            if ($stmt_check->fetch()) {
                // Actualizar calificación existente
                $sql = "UPDATE notas SET nota = ?, observaciones = ? 
                        WHERE id_estudiante = ? AND id_actividad = ?";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([$nota, $observaciones, $id_estudiante, $id_actividad]);
            } else {
                // Crear nueva calificación
                $sql = "INSERT INTO notas (id_estudiante, id_curso, id_actividad, nota, observaciones) 
                        VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([$id_estudiante, $id_curso, $id_actividad, $nota, $observaciones]);
            }
        } catch (PDOException $e) {
            throw new Exception("Error al calificar: " . $e->getMessage());
        }
    }

    // ============================================
    // 4. DASHBOARD DE DESEMPEÑO
    // ============================================

    /**
     * Obtener estadísticas de rendimiento por curso
     */
    public function obtenerEstadisticasCurso($id_curso) {
        try {
            $sql = "SELECT 
                        COUNT(DISTINCT ec.id_estudiante) as total_estudiantes,
                        COUNT(DISTINCT a.id_actividad) as total_actividades,
                        COUNT(n.id_nota) as total_calificaciones,
                        AVG(n.nota) as promedio_general,
                        COUNT(CASE WHEN n.nota >= 70 THEN 1 END) as aprobados,
                        COUNT(CASE WHEN n.nota < 70 THEN 1 END) as reprobados
                    FROM cursos c
                    LEFT JOIN estudiantes_cursos ec ON c.id_curso = ec.id_curso
                    LEFT JOIN actividades a ON c.id_curso = a.id_curso
                    LEFT JOIN notas n ON a.id_actividad = n.id_actividad
                    WHERE c.id_curso = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_curso]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new Exception("Error al obtener estadísticas: " . $e->getMessage());
        }
    }

    /**
     * Identificar estudiantes en riesgo
     */
    public function obtenerEstudiantesEnRiesgo($id_curso, $limite_nota = 70) {
        try {
            $sql = "SELECT u.id_usuario, u.nombre, u.correo, 
                           AVG(n.nota) as promedio,
                           COUNT(n.id_nota) as actividades_calificadas,
                           COUNT(a.id_actividad) as total_actividades
                    FROM usuarios u
                    INNER JOIN estudiantes_cursos ec ON u.id_usuario = ec.id_estudiante
                    LEFT JOIN actividades a ON ec.id_curso = a.id_curso
                    LEFT JOIN notas n ON u.id_usuario = n.id_estudiante AND a.id_actividad = n.id_actividad
                    WHERE ec.id_curso = ? AND u.rol = 'estudiante'
                    GROUP BY u.id_usuario
                    HAVING promedio < ? OR actividades_calificadas < total_actividades * 0.5";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_curso, $limite_nota]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Error al obtener estudiantes en riesgo: " . $e->getMessage());
        }
    }

    // ============================================
    // 5. CREACIÓN DE MISIONES/DESAFÍOS
    // ============================================

    /**
     * Crear nueva misión
     */
    public function crearMision($titulo, $descripcion, $recompensa, $id_grupo = null) {
        try {
            $sql = "INSERT INTO misiones (titulo, descripcion, recompensa, id_grupo) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$titulo, $descripcion, $recompensa, $id_grupo]);
            
            if ($result) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            throw new Exception("Error al crear misión: " . $e->getMessage());
        }
    }

    /**
     * Obtener misiones por grupo
     */
    public function obtenerMisionesPorGrupo($id_grupo = null) {
        try {
            if ($id_grupo) {
                $sql = "SELECT * FROM misiones WHERE id_grupo = ? OR id_grupo IS NULL ORDER BY id_mision DESC";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$id_grupo]);
            } else {
                $sql = "SELECT * FROM misiones WHERE id_grupo IS NULL ORDER BY id_mision DESC";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
            }
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Error al obtener misiones: " . $e->getMessage());
        }
    }

    // ============================================
    // 6. SISTEMA DE NOTIFICACIONES
    // ============================================

    /**
     * Crear notificación
     */
    public function crearNotificacion($id_usuario, $titulo, $mensaje, $tipo = 'Sistema') {
        try {
            $sql = "INSERT INTO notificaciones (id_usuario, titulo, mensaje, tipo) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id_usuario, $titulo, $mensaje, $tipo]);
        } catch (PDOException $e) {
            throw new Exception("Error al crear notificación: " . $e->getMessage());
        }
    }

    /**
     * Enviar notificación masiva a estudiantes de un curso
     */
    public function notificarEstudiantesCurso($id_curso, $titulo, $mensaje, $tipo = 'Curso') {
        try {
            $this->db->beginTransaction();
            
            // Obtener estudiantes del curso
            $sql_estudiantes = "SELECT id_estudiante FROM estudiantes_cursos WHERE id_curso = ?";
            $stmt_estudiantes = $this->db->prepare($sql_estudiantes);
            $stmt_estudiantes->execute([$id_curso]);
            $estudiantes = $stmt_estudiantes->fetchAll();
            
            // Crear notificación para cada estudiante
            $sql_notif = "INSERT INTO notificaciones (id_usuario, titulo, mensaje, tipo) VALUES (?, ?, ?, ?)";
            $stmt_notif = $this->db->prepare($sql_notif);
            
            foreach ($estudiantes as $estudiante) {
                $stmt_notif->execute([$estudiante['id_estudiante'], $titulo, $mensaje, $tipo]);
            }
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new Exception("Error al enviar notificaciones: " . $e->getMessage());
        }
    }

    // ============================================
    // 7. CONFIGURACIÓN DE PERFIL
    // ============================================

    /**
     * Actualizar perfil del docente
     */
    public function actualizarPerfil($id_usuario, $nombre, $correo) {
        try {
            $sql = "UPDATE usuarios SET nombre = ?, correo = ? WHERE id_usuario = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nombre, $correo, $id_usuario]);
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar perfil: " . $e->getMessage());
        }
    }

    /**
     * Cambiar contraseña
     */
    public function cambiarContrasena($id_usuario, $nueva_contrasena) {
        try {
            $hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET contrasena = ? WHERE id_usuario = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$hash, $id_usuario]);
        } catch (PDOException $e) {
            throw new Exception("Error al cambiar contraseña: " . $e->getMessage());
        }
    }

    /**
     * Verificar contraseña actual
     */
    public function verificarContrasena($id_usuario, $contrasena_actual) {
        try {
            $sql = "SELECT contrasena FROM usuarios WHERE id_usuario = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_usuario]);
            $usuario = $stmt->fetch();
            
            if ($usuario) {
                return password_verify($contrasena_actual, $usuario['contrasena']);
            }
            return false;
        } catch (PDOException $e) {
            throw new Exception("Error al verificar contraseña: " . $e->getMessage());
        }
    }

    // ============================================
    // MÉTODOS AUXILIARES
    // ============================================

    /**
     * Obtener cursos del docente
     */
    public function obtenerCursosDocente($id_docente) {
        try {
            $sql = "SELECT * FROM cursos WHERE id_docente = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_docente]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Error al obtener cursos: " . $e->getMessage());
        }
    }

    /**
     * Obtener información de un curso específico
     */
    public function obtenerCurso($id_curso) {
        try {
            $sql = "SELECT * FROM cursos WHERE id_curso = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_curso]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new Exception("Error al obtener curso: " . $e->getMessage());
        }
    }
}
?>