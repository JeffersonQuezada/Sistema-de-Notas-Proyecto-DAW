<?php
// filepath: admin/controllers/MisionController.php
require_once '../models/MisionModel.php';
if (session_status() === PHP_SESSION_NONE) session_start();

class MisionController {
    private $misionModel;
    public function __construct() {
        $this->misionModel = new MisionModel();
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $recompensa = $_POST['recompensa'];
            $id_grupo = $_POST['id_grupo'];
            $id_mision = $this->misionModel->crearMision($titulo, $descripcion, $recompensa, $id_grupo);
            $this->misionModel->asignarMisionAGrupo($id_mision, $id_grupo);

            header("Location: ../index.php?accion=misiones&success=1");
            exit();
        }
        // Aquí deberías cargar los grupos para el select
        include '../views/misiones_crear.php';
    }

    public function listar() {
        // Aquí puedes listar todas las misiones o por grupo
        include '../views/misiones_listado.php';
    }
}
?>