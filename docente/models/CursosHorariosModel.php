<?php
require_once __DIR__ . '/../../includes/conexion.php';

class CursosHorariosModel {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    public function obtenerCursosConHorarios() {
        $stmt = $this->pdo->query("
            SELECT 
                c.id_curso,
                c.nombre_curso,
                c.descripcion,
                c.capacidad,
                c.grupo,
                u.nombre as nombre_docente,
                u.id_usuario as id_docente,
                h.dia_semana,
                h.hora_inicio,
                h.hora_fin,
                h.aula,
                COUNT(ec.id_estudiante) as estudiantes_inscritos
            FROM cursos c
            JOIN usuarios u ON c.id_docente = u.id_usuario
            LEFT JOIN horarios h ON c.id_curso = h.id_curso
            LEFT JOIN estudiantes_cursos ec ON c.id_curso = ec.id_curso
            GROUP BY c.id_curso, h.id_horario
            ORDER BY c.nombre_curso, h.dia_semana, h.hora_inicio
        ");
        return $stmt->fetchAll();
    }
    
    public function obtenerCursosPorDocente($id_docente) {
        $stmt = $this->pdo->prepare("
            SELECT 
                c.id_curso,
                c.nombre_curso,
                c.descripcion,
                c.capacidad,
                c.grupo,
                h.dia_semana,
                h.hora_inicio,
                h.hora_fin,
                h.aula,
                COUNT(ec.id_estudiante) as estudiantes_inscritos
            FROM cursos c
            LEFT JOIN horarios h ON c.id_curso = h.id_curso
            LEFT JOIN estudiantes_cursos ec ON c.id_curso = ec.id_curso
            WHERE c.id_docente = ?
            GROUP BY c.id_curso, h.id_horario
            ORDER BY c.nombre_curso, h.dia_semana, h.hora_inicio
        ");
        $stmt->execute([$id_docente]);
        return $stmt->fetchAll();
    }
    
    public function obtenerDocentes() {
        $stmt = $this->pdo->query("
            SELECT 
                u.id_usuario, 
                u.nombre,
                COUNT(c.id_curso) as total_cursos
            FROM usuarios u
            LEFT JOIN cursos c ON u.id_usuario = c.id_docente
            WHERE u.rol = 'docente'
            GROUP BY u.id_usuario
            ORDER BY u.nombre
        ");
        return $stmt->fetchAll();
    }
    
    public function obtenerDocentePorId($id_docente) {
        $stmt = $this->pdo->prepare("
            SELECT id_usuario, nombre, correo 
            FROM usuarios 
            WHERE id_usuario = ? AND rol = 'docente'
        ");
        $stmt->execute([$id_docente]);
        return $stmt->fetch();
    }
    
    public function asignarHorario($datos) {
        try {
            // Verificar si ya existe un horario para ese curso en ese día y hora
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as conflictos 
                FROM horarios 
                WHERE id_curso = ? AND dia_semana = ? 
                AND ((hora_inicio <= ? AND hora_fin > ?) OR (hora_inicio < ? AND hora_fin >= ?))
            ");
            $stmt->execute([
                $datos['id_curso'], 
                $datos['dia_semana'], 
                $datos['hora_inicio'], $datos['hora_inicio'],
                $datos['hora_fin'], $datos['hora_fin']
            ]);
            
            if ($stmt->fetch()['conflictos'] > 0) {
                $_SESSION['error'] = 'Ya existe un horario conflictivo para este curso';
                return false;
            }
            
            $stmt = $this->pdo->prepare("
                INSERT INTO horarios (id_curso, dia_semana, hora_inicio, hora_fin, aula) 
                VALUES (?, ?, ?, ?, ?)
            ");
            
            return $stmt->execute([
                $datos['id_curso'],
                $datos['dia_semana'],
                $datos['hora_inicio'],
                $datos['hora_fin'],
                $datos['aula'] ?? null
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function actualizarHorario($id_curso, $datos) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE horarios 
                SET dia_semana = ?, hora_inicio = ?, hora_fin = ?, aula = ?
                WHERE id_curso = ?
            ");
            
            return $stmt->execute([
                $datos['dia_semana'],
                $datos['hora_inicio'],
                $datos['hora_fin'],
                $datos['aula'] ?? null,
                $id_curso
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function obtenerHorariosCurso($id_curso) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM horarios WHERE id_curso = ? ORDER BY dia_semana, hora_inicio
        ");
        $stmt->execute([$id_curso]);
        return $stmt->fetchAll();
    }
}