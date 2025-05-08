<?php
class Usuario {
    private $pdo;
    private $id;
    private $nombre;
    private $correo;
    private $rol;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function cargar($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$id]);
        if ($usuario = $stmt->fetch()) {
            $this->id = $usuario['id_usuario'];
            $this->nombre = $usuario['nombre'];
            $this->correo = $usuario['correo'];
            $this->rol = $usuario['rol'];
            return true;
        }
        return false;
    }
    
    // Métodos getters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getCorreo() { return $this->correo; }
    public function getRol() { return $this->rol; }
    
    // Otros métodos según necesidad
}
?>