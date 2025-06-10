<?php
require_once __DIR__ . '/../../includes/conexion.php';

class ReporteModel {
    private $pdo;
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    public function estadisticasGenerales() {
        $sql = "SELECT 
            (SELECT COUNT(*) FROM usuarios WHERE rol = 'docente') as docentes,
            (SELECT COUNT(*) FROM usuarios WHERE rol = 'estudiante') as estudiantes,
            (SELECT COUNT(*) FROM cursos) as cursos,
            (SELECT COUNT(*) FROM notas) as calificaciones";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function generarReporteUsuarios() {
        $sql = "SELECT nombre, correo, rol, fecha_registro FROM usuarios ORDER BY rol, nombre";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    // Puedes agregar métodos para reportes PDF/Excel aquí
}
?>