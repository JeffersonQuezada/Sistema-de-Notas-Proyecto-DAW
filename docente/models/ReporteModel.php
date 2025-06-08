<?php
require_once __DIR__ . '/../../includes/conexion.php';
// require_once __DIR__ . '/../../vendor/autoload.php'; // Descomenta si usas Composer y Dompdf/PhpSpreadsheet

class ReporteModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    // Obtener datos de reporte
    public function obtenerReporteCalificaciones($id_curso) {
        $sql = "SELECT u.nombre as estudiante, a.nombre as actividad, n.nota
                FROM notas n
                JOIN usuarios u ON n.id_estudiante = u.id_usuario
                JOIN actividades a ON n.id_actividad = a.id_actividad
                WHERE a.id_curso = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_curso]);
        return $stmt->fetchAll();
    }

    // Generar PDF con Dompdf
    public function generarReportePDF($id_curso) {
        // require Dompdf solo si lo usas
        // use Dompdf\Dompdf;
        $datos = $this->obtenerReporteCalificaciones($id_curso);

        $html = '<h1>Reporte de Calificaciones</h1>';
        $html .= '<table border="1" cellpadding="5">';
        $html .= '<tr><th>Estudiante</th><th>Actividad</th><th>Calificación</th></tr>';

        foreach ($datos as $row) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($row['estudiante']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['actividad']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['nota']) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        // Si tienes Dompdf instalado, descomenta lo siguiente:
        /*
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("reporte.pdf", ["Attachment" => false]);
        */
        // Si no, simplemente retorna el HTML
        return $html;
    }

    // Generar Excel con PhpSpreadsheet
    public function generarReporteExcel($id_curso) {
        // use PhpOffice\PhpSpreadsheet\Spreadsheet;
        // use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

        $datos = $this->obtenerReporteCalificaciones($id_curso);

        // Si tienes PhpSpreadsheet instalado, descomenta lo siguiente:
        /*
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Estudiante');
        $sheet->setCellValue('B1', 'Actividad');
        $sheet->setCellValue('C1', 'Calificación');

        $row = 2;
        foreach ($datos as $data) {
            $sheet->setCellValue('A' . $row, $data['estudiante']);
            $sheet->setCellValue('B' . $row, $data['actividad']);
            $sheet->setCellValue('C' . $row, $data['nota']);
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        // Puedes guardar o enviar el archivo al navegador
        $filename = 'reporte.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
        */
        // Si no, retorna los datos
        return $datos;
    }
}
?>