<?php
require_once __DIR__ . '/../models/MisionModel.php';
require_once __DIR__ . '/../models/EntregaModel.php'; // Asegúrate de incluir el modelo de entrega
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

        include __DIR__ . '/../views/misiones.php';
    }

    public function aceptar($id_mision) {
        $id_estudiante = $_SESSION['id_usuario'];
        $this->misionModel->aceptarMision($id_estudiante, $id_mision);
        header("Location: ../index.php?accion=misiones&success=1");
        exit();
    }
}
?>