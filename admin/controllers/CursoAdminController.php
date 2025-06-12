<?php
require_once __DIR__ . '/../models/CursoAdminModel.php';
require_once __DIR__ . '/../views/ListaCursosView.php';
require_once __DIR__ . '/../views/FormularioCursoView.php';

class CursoAdminController {
    private $model;
    
    public function __construct() {
        $this->model = new CursoAdminModel();
    }
    
    public function listarCursos() {
        $busqueda = $_GET['busqueda'] ?? '';
        if ($busqueda !== '') {
            $cursos = $this->model->buscar($busqueda);
        } else {
            $cursos = $this->model->obtenerTodos();
        }
        $view = new ListaCursosView();
        $view->mostrar($cursos);
    }
    
    public function mostrarFormularioCreacionCurso() {
        $docentes = $this->model->obtenerDocentes();
        $view = new FormularioCursoView();
        $view->mostrar(null, 'Crear Curso', $docentes);
    }
    
    public function crearCurso($datos) {
        $this->model->crearCurso($datos);
        header('Location: index.php?accion=cursos_admin');
        exit;
    }
    
    public function mostrarFormularioEdicionCurso($id) {
        $curso = $this->model->obtenerPorId($id);
        $docentes = $this->model->obtenerDocentes();
        if ($curso) {
            $view = new FormularioCursoView();
            $view->mostrar($curso, 'Editar Curso', $docentes);
        } else {
            $_SESSION['error'] = 'Curso no encontrado';
            header('Location: index.php?accion=cursos_admin');
        }
    }
    
    public function actualizarCurso($id, $datos) {
        $resultado = $this->model->actualizar($id, $datos);
        if ($resultado) {
            $_SESSION['mensaje'] = 'Curso actualizado exitosamente';
            header('Location: index.php?accion=cursos_admin');
        } else {
            $_SESSION['error'] = 'Error al actualizar el curso';
            header("Location: index.php?accion=editar_curso&id=$id");
        }
    }
    
    public function eliminarCurso($id) {
        $resultado = $this->model->eliminar($id);
        if ($resultado) {
            $_SESSION['mensaje'] = 'Curso eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el curso';
        }
        header('Location: index.php?accion=cursos_admin');
    }
    
    public function exportarCursosPDF() {
        require_once __DIR__ . '/../../libs/fpdf/fpdf.php';
        $cursos = $this->model->obtenerTodos();
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Listado de Cursos', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        foreach ($cursos as $curso) {
            $pdf->Cell(0, 8, $curso['nombre_curso'] . ' - Docente: ' . $curso['nombre_docente'], 0, 1);
        }
        header('Content-Type: application/pdf');
        $pdf->Output('I', 'cursos.pdf');
        exit;
    }
    
    public function exportarCursosExcel() {
        require_once __DIR__ . '/../../vendor/autoload.php';
        $cursos = $this->model->obtenerTodos();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Nombre del Curso');
        $sheet->setCellValue('B1', 'Docente');
        $row = 2;
        foreach ($cursos as $curso) {
            $sheet->setCellValue('A' . $row, $curso['nombre_curso']);
            $sheet->setCellValue('B' . $row, $curso['nombre_docente']);
            $row++;
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="cursos.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    

}