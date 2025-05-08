<?php
class Curso {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function crear($nombre, $descripcion, $idDocente, $contrasena, $capacidad = 50) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO cursos (nombre_curso, descripcion, id_docente, contrasena, capacidad) 
                                         VALUES (?, ?, ?, ?, ?)");
            return $stmt->execute([$nombre, $descripcion, $idDocente, password_hash($contrasena, PASSWORD_DEFAULT), $capacidad]);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    public function asignarHorario($idCurso, $dia, $horaInicio, $horaFin) {
        // Verificar conflicto de horarios para el docente
        if (!$this->verificarDisponibilidadHorario($idCurso, $dia, $horaInicio, $horaFin)) {
            return false;
        }
        
        $stmt = $this->pdo->prepare("INSERT INTO horarios (id_curso, dia_semana, hora_inicio, hora_fin) 
                                     VALUES (?, ?, ?, ?)");
        return $stmt->execute([$idCurso, $dia, $horaInicio, $horaFin]);
    }
    
    private function verificarDisponibilidadHorario($idCurso, $dia, $horaInicio, $horaFin) {
        // Implementar lógica para verificar que no hay conflictos
        return true;
    }
}
?>
