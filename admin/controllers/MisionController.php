<?php
require_once __DIR__ . '/../models/MisionModel.php';
require_once __DIR__ . '/../views/MisionesView.php';

class MisionController {
    private $model;
    public function __construct() {
        $this->model = new MisionModel();
    }

    public function listarMisiones() {
        $misiones = $this->model->obtenerTodas();
        $view = new MisionesView();
        $view->mostrar($misiones);
    }

    public function mostrarFormularioCreacion() {
        $profesores = $this->model->obtenerUsuariosPorRol('docente');
        $estudiantes = $this->model->obtenerUsuariosPorRol('estudiante');
        $cursos = $this->model->obtenerTodosLosCursos();
        $view = new MisionesView();
        $view->mostrarFormularioCreacion($profesores, $estudiantes, $cursos, $profesores, $estudiantes);
    }

    public function crearMision($datos) {
        $this->model->crear($datos);
        $misionId = $this->model->obtenerUltimoIdInsertado();

        // Asignar a profesores
        if (!empty($datos['profesores'])) {
            foreach ($datos['profesores'] as $id_usuario) {
                $this->model->asignarMision($misionId, $id_usuario);
            }
        }
        // Asignar a estudiantes
        if (!empty($datos['estudiantes'])) {
            foreach ($datos['estudiantes'] as $id_usuario) {
                $this->model->asignarMision($misionId, $id_usuario);
            }
        }
        // Asignar a todos los usuarios de los cursos seleccionados
        if (!empty($datos['cursos'])) {
            foreach ($datos['cursos'] as $id_curso) {
                $usuarios = $this->model->obtenerUsuariosPorCurso($id_curso);
                foreach ($usuarios as $u) {
                    $this->model->asignarMision($misionId, $u['id_usuario']);
                }
            }
        }
        header('Location: index.php?accion=misiones');
        exit;
    }

    public function entregarActividad($id_actividad, $datos) {
        $this->model->entregarActividad($id_actividad, $_SESSION['id_usuario'], $datos);
        // Lógica de insignias automáticas
        require_once __DIR__ . '/../models/InsigniaModel.php';
        $insigniaModel = new InsigniaModel();
        // Obtén el curso de la actividad
        $id_curso = $this->model->obtenerCursoPorActividad($id_actividad);
        $insigniaModel->asignarInsigniaSiCorresponde($_SESSION['id_usuario'], $id_curso);
        // Redirige o muestra mensaje
    }
}