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
        $cursos = $this->model->obtenerTodos();
        $view = new ListaCursosView();
        $view->mostrar($cursos);
    }
    
    public function mostrarFormularioCreacionCurso() {
        $docentes = $this->model->obtenerDocentes();
        $view = new FormularioCursoView();
        $view->mostrar(null, 'Crear Curso', $docentes);
    }
    
    public function crearCurso($datos) {
        $resultado = $this->model->crear($datos);
        if ($resultado) {
            $_SESSION['mensaje'] = 'Curso creado exitosamente';
            header('Location: index.php?accion=cursos_admin');
        } else {
            $_SESSION['error'] = 'Error al crear el curso';
            $docentes = $this->model->obtenerDocentes();
            $view = new FormularioCursoView();
            $view->mostrar($datos, 'Crear Curso', $docentes);
        }
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
}