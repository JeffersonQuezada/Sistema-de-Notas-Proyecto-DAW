<?php
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../views/ListaUsuariosView.php';
require_once __DIR__ . '/../views/FormularioUsuarioView.php';

class UsuarioController {
    private $model;
    
    public function __construct() {
        $this->model = new UsuarioModel();
    }
    
    public function listarUsuarios() {
        $usuarios = $this->model->obtenerTodos();
        $view = new ListaUsuariosView();
        $view->mostrar($usuarios);
    }
    
    public function mostrarFormularioCreacion() {
        $view = new FormularioUsuarioView();
        $view->mostrar(null, 'Crear Usuario');
    }
    
    public function crearUsuario($datos) {
        $resultado = $this->model->crear($datos);
        if ($resultado) {
            $_SESSION['mensaje'] = 'Usuario creado exitosamente';
            header('Location: index.php?accion=usuarios');
        } else {
            $_SESSION['error'] = 'Error al crear el usuario';
            $view = new FormularioUsuarioView();
            $view->mostrar($datos, 'Crear Usuario');
        }
    }
    
    public function mostrarFormularioEdicion($id) {
        $usuario = $this->model->obtenerPorId($id);
        if ($usuario) {
            $view = new FormularioUsuarioView();
            $view->mostrar($usuario, 'Editar Usuario');
        } else {
            $_SESSION['error'] = 'Usuario no encontrado';
            header('Location: index.php?accion=usuarios');
        }
    }
    
    public function actualizarUsuario($id, $datos) {
        $resultado = $this->model->actualizar($id, $datos);
        if ($resultado) {
            $_SESSION['mensaje'] = 'Usuario actualizado exitosamente';
            header('Location: index.php?accion=usuarios');
        } else {
            $_SESSION['error'] = 'Error al actualizar el usuario';
            header("Location: index.php?accion=editar_usuario&id=$id");
        }
    }
    
    public function eliminarUsuario($id) {
        $resultado = $this->model->eliminar($id);
        if ($resultado) {
            $_SESSION['mensaje'] = 'Usuario eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el usuario';
        }
        header('Location: index.php?accion=usuarios');
    }
}