<?php
require_once __DIR__ . '/../models/CursosHorariosModel.php';
require_once __DIR__ . '/../views/CursosHorariosView.php';

class CursosHorariosController {
    private $model;
    
    public function __construct() {
        $this->model = new CursosHorariosModel();
    }
    
    public function mostrarCursosHorarios() {
        $cursosConHorarios = $this->model->obtenerCursosConHorarios();
        $docentes = $this->model->obtenerDocentes();
        $view = new CursosHorariosView();
        $view->mostrar($cursosConHorarios, $docentes);
    }
    
    public function mostrarCursosPorDocente($id_docente) {
        $cursos = $this->model->obtenerCursosPorDocente($id_docente);
        $docente = $this->model->obtenerDocentePorId($id_docente);
        $view = new CursosHorariosView();
        $view->mostrarCursosPorDocente($cursos, $docente);
    }
    
    public function asignarHorario($datos) {
        $resultado = $this->model->asignarHorario($datos);
        if ($resultado) {
            $_SESSION['mensaje'] = 'Horario asignado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al asignar el horario';
        }
        header('Location: index.php?accion=cursos_horarios');
    }
    
    public function actualizarHorario($id_curso, $datos) {
        $resultado = $this->model->actualizarHorario($id_curso, $datos);
        if ($resultado) {
            $_SESSION['mensaje'] = 'Horario actualizado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al actualizar el horario';
        }
        header('Location: index.php?accion=cursos_horarios');
    }
}