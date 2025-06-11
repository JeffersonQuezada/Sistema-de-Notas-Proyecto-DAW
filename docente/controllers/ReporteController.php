<?php
require_once __DIR__ . '/../models/ReporteModel.php';
require_once __DIR__ . '/../models/CursoModel.php';

class ReporteController {
    private $reporteModel;
    private $cursoModel;

    public function __construct() {
        $this->reporteModel = new ReporteModel();
        $this->cursoModel = new CursoModel();
    }

    public function generarReportePDF($id_curso) {
        try {
            // Verificar permisos
            if ($_SESSION['rol'] === 'docente') {
                $curso = $this->cursoModel->obtenerCursoPorId($id_curso);
                if (!$curso || $curso['id_docente'] != $_SESSION['id_usuario']) {
                    throw new Exception("No tienes permisos para generar reportes de este curso");
                }
            }

            $reporte = $this->reporteModel->generarReportePDF($id_curso);
            
            // Configurar headers para PDF
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="reporte_curso_' . $id_curso . '.pdf"');
            echo $reporte;
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: index.php?action=ver_curso&id=$id_curso");
            exit();
        }
    }

    public function generarReporteExcel($id_curso) {
        try {
            // Verificar permisos
            if ($_SESSION['rol'] === 'docente') {
                $curso = $this->cursoModel->obtenerCursoPorId($id_curso);
                if (!$curso || $curso['id_docente'] != $_SESSION['id_usuario']) {
                    throw new Exception("No tienes permisos para generar reportes de este curso");
                }
            }

            $datos = $this->reporteModel->generarReporteExcel($id_curso);
            
            // Configurar headers para Excel
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="reporte_curso_' . $id_curso . '.xls"');
            
            // Generar contenido HTML para Excel
            echo '<table border="1">';
            echo '<tr><th>Estudiante</th><th>Actividad</th><th>Calificación</th><th>Observaciones</th></tr>';
            
            foreach ($datos as $fila) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($fila['estudiante']) . '</td>';
                echo '<td>' . htmlspecialchars($fila['actividad']) . '</td>';
                echo '<td>' . htmlspecialchars($fila['nota']) . '</td>';
                echo '<td>' . htmlspecialchars($fila['observaciones'] ?? '') . '</td>';
                echo '</tr>';
            }
            
            echo '</table>';
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: index.php?action=ver_curso&id=$id_curso");
            exit();
        }
    }
}
?>