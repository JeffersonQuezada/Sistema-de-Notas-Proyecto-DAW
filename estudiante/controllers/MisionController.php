<?php
require_once '../models/MisionModel.php';
require_once '../models/EntregaModel.php'; // Asegúrate de incluir el modelo de entrega
if (session_status() === PHP_SESSION_NONE) session_start();

class MisionController {
    private $misionModel;
    private $entregaModel; // Agrega la propiedad para el modelo de entrega

    public function __construct() {
        $this->misionModel = new MisionModel();
        $this->entregaModel = new EntregaModel(); // Inicializa el modelo de entrega
    }

    public function mostrarMisiones() {
        $id_estudiante = $_SESSION['id_usuario'];
        $misiones = $this->misionModel->listarMisionesDisponibles($id_estudiante);

        // Obtén el promedio general del estudiante
        $promedio = $this->entregaModel->obtenerPromedioGeneral($id_estudiante);

        include '../views/misiones.php';
    }

    public function aceptar($id_mision) {
        $id_estudiante = $_SESSION['id_usuario'];
        $this->misionModel->aceptarMision($id_estudiante, $id_mision);
        header("Location: ../views/misiones.php?success=1");
    }
}
?>