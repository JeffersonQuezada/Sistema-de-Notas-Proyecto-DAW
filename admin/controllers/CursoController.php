<?php
require_once __DIR__ . '/../models/CursoModel.php';
if (session_status() === PHP_SESSION_NONE) session_start();

class CursoController {
    private $cursoModel;
    public function __construct() {
        $this->cursoModel = new CursoModel();
    }
    public function listar() {
        $cursos = $this->cursoModel->listarCursos();
        include '../views/cursos_listado.php';
    }
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $id_docente = $_POST['id_docente'];
            $contrasena = $_POST['contrasena'];
            $capacidad = $_POST['capacidad'];
            $this->cursoModel->crearCurso($nombre, $descripcion, $id_docente, $contrasena, $capacidad);
            header("Location: cursos_listado.php?success=1");
            exit();
        }
        include '../views/cursos_crear.php';
    }
    // Métodos para editar y eliminar...
}

include __DIR__ . '/../views/cursos_listado.php';
?>