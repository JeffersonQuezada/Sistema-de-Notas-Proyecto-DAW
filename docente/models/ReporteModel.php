<?php
require_once '../includes/Database.php';
require_once '../vendor/autoload.php'; // Asegúrate de tener las librerías de generación de PDF y Excel instaladas

use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReporteModel {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function generarReportePDF($id_curso, $filtros) {
        // Lógica para generar el reporte en PDF
        $html = '<h1>Reporte de Calificaciones</h1>';
        $html .= '<table border="1">';
        $html .= '<tr><th>Estudiante</th><th>Actividad</th><th>Calificación</th></tr>';

        // Obtener datos de la base de datos según los filtros
        $sql = "SELECT u.nombre as estudiante, a.titulo as actividad, n.nota
                FROM notas n
                JOIN usuarios u ON n.id_estudiante = u.id
                JOIN actividades a ON n.id_actividad = a.id
                WHERE a.id_curso = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_curso]);
        $results = $stmt->fetchAll();

        foreach ($results as $row) {
            $html .= '<tr>';
            $html .= '<td>' . $row['estudiante'] . '</td>';
            $html .= '<td>' . $row['actividad'] . '</td>';
            $html .= '<td>' . $row['nota'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("reporte.pdf", ["Attachment" => false]);
    }

    public function generarReporteExcel($id_curso, $filtros) {
        // Lógica para generar el reporte en Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Estudiante');
        $sheet->setCellValue('B1', 'Actividad');
        $sheet->setCellValue('C1', 'Calificación');

        // Obtener datos de la base de datos según los filtros
        $sql = "SELECT u.nombre as estudiante, a.titulo as actividad, n.nota
                FROM notas n
                JOIN usuarios u ON n.id_estudiante = u.id
                JOIN actividades a ON n.id_actividad = a.id
                WHERE a.id_curso = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_curso]);
        $results = $stmt->fetchAll();

        $row = 2;
        foreach ($results as $data) {
            $sheet->setCellValue('A' . $row, $data['estudiante']);
            $sheet->setCellValue('B' . $row, $data['actividad']);
            $sheet->setCellValue('C' . $row, $data['nota']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save('reporte.xlsx');
    }
}
?>