<?php
require_once __DIR__ . '/../models/CursoModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';

class CursoController {
    private $cursoModel;
    private $usuarioModel;

    public function __construct() {
        $this->cursoModel = new CursoModel();
        $this->usuarioModel = new UsuarioModel();
    }

    public function mostrarCursos() {
        try {
            $cursos = [];
            
            if ($_SESSION['rol'] === 'docente') {
                $cursos = $this->cursoModel->listarCursosPorDocente($_SESSION['id_usuario']);
            } elseif ($_SESSION['rol'] === 'admin') {
                $cursos = $this->cursoModel->listarCursos();
            } elseif ($_SESSION['rol'] === 'estudiante') {
                $cursos = $this->cursoModel->listarCursosPorEstudiante($_SESSION['id_usuario']);
            }
            
            include __DIR__ . '/../views/cursos_listado.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al cargar los cursos: " . $e->getMessage();
            header("Location: ../views/error.php");
            exit();
        }
    }

    public function crearCurso() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nombre = trim($_POST['nombre'] ?? '');
                $descripcion = trim($_POST['descripcion'] ?? '');
                $codigo = trim($_POST['codigo'] ?? '');
                $contrasena = password_hash($_POST['contrasena'] ?? '', PASSWORD_DEFAULT);
                $capacidad = (int)($_POST['capacidad'] ?? 50);
                $grupo = $_POST['grupo'] ?? null;

                // Validaciones básicas
                if (empty($nombre) || empty($codigo)) {
                    throw new Exception("Nombre y código son campos obligatorios");
                }

                if ($capacidad < 1 || $capacidad > 100) {
                    throw new Exception("La capacidad debe estar entre 1 y 100");
                }

                // Verificar si el código ya existe
                if ($this->cursoModel->verificarCodigoExiste($codigo)) {
                    throw new Exception("El código del curso ya existe");
                }

                $id_docente = $_SESSION['id_usuario'];
                
                $resultado = $this->cursoModel->crearCurso($nombre, $descripcion, $codigo, $id_docente, $contrasena, $capacidad, $grupo);

                if ($resultado) {
                    $_SESSION['success'] = "Curso creado exitosamente";
                    header("Location: index.php?action=cursos");
                    exit();
                } else {
                    throw new Exception("Error al crear el curso");
                }
            }

            // Si es admin, obtener lista de docentes para asignar
            $docentes = [];
            if ($_SESSION['rol'] === 'admin') {
                $docentes = $this->usuarioModel->listarUsuariosPorRol('docente');
            }

            include __DIR__ . '/../views/curso_crear.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: index.php?action=crear_curso");
            exit();
        }
    }

    public function editarCurso($id_curso) {
        try {
            $curso = $this->cursoModel->obtenerCursoPorId($id_curso);
            
            if (!$curso) {
                throw new Exception("Curso no encontrado");
            }

            // Verificar permisos
            if ($_SESSION['rol'] === 'docente' && $curso['id_docente'] != $_SESSION['id_usuario']) {
                throw new Exception("No tienes permisos para editar este curso");
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nombre = trim($_POST['nombre'] ?? '');
                $descripcion = trim($_POST['descripcion'] ?? '');
                $codigo = trim($_POST['codigo'] ?? '');
                $capacidad = (int)($_POST['capacidad'] ?? 50);
                $grupo = $_POST['grupo'] ?? null;

                // Validaciones
                if (empty($nombre) || empty($codigo)) {
                    throw new Exception("Nombre y código son campos obligatorios");
                }

                // Verificar si el código ya existe (excluyendo este curso)
                if ($this->cursoModel->verificarCodigoExiste($codigo, $id_curso)) {
                    throw new Exception("El código del curso ya existe");
                }

                $resultado = $this->cursoModel->actualizarCurso(
                    $id_curso, 
                    $nombre, 
                    $descripcion, 
                    $codigo, 
                    $capacidad, 
                    $grupo
                );

                if ($resultado) {
                    $_SESSION['success'] = "Curso actualizado exitosamente";
                    header("Location: index.php?action=ver_curso&id=$id_curso");
                    exit();
                } else {
                    throw new Exception("Error al actualizar el curso");
                }
            }

            // Si es admin, obtener lista de docentes
            $docentes = [];
            if ($_SESSION['rol'] === 'admin') {
                $docentes = $this->usuarioModel->listarUsuariosPorRol('docente');
            }

            include __DIR__ . '/../views/curso_editar.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: index.php?action=cursos");
            exit();
        }
    }

    public function eliminarCurso($id_curso) {
        try {
            $curso = $this->cursoModel->obtenerCursoPorId($id_curso);
            
            if (!$curso) {
                throw new Exception("Curso no encontrado");
            }

            // Verificar permisos
            if ($_SESSION['rol'] === 'docente' && $curso['id_docente'] != $_SESSION['id_usuario']) {
                throw new Exception("No tienes permisos para eliminar este curso");
            }

            $resultado = $this->cursoModel->eliminarCurso($id_curso);

            if ($resultado) {
                $_SESSION['success'] = "Curso eliminado exitosamente";
            } else {
                throw new Exception("Error al eliminar el curso");
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header("Location: index.php?action=cursos");
        exit();
    }

    public function inscribirEstudiante($id_curso, $id_estudiante) {
        try {
            // Verificar que el curso existe y el usuario es estudiante
            $curso = $this->cursoModel->obtenerCursoPorId($id_curso);
            $estudiante = $this->usuarioModel->obtenerUsuarioPorId($id_estudiante);
            
            if (!$curso || !$estudiante || $estudiante['rol'] !== 'estudiante') {
                throw new Exception("Datos inválidos para la inscripción");
            }

            // Verificar permisos (docente del curso o admin)
            if ($_SESSION['rol'] === 'docente' && $curso['id_docente'] != $_SESSION['id_usuario']) {
                throw new Exception("No tienes permisos para inscribir en este curso");
            }

            $resultado = $this->cursoModel->inscribirEstudiante($id_curso, $id_estudiante);

            if ($resultado) {
                $_SESSION['success'] = "Estudiante inscrito correctamente";
            } else {
                throw new Exception("Error al inscribir estudiante");
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header("Location: index.php?action=ver_curso&id=$id_curso");
        exit();
    }

    public function desinscribirEstudiante($id_curso, $id_estudiante) {
        try {
            // Verificar que la relación existe
            $inscrito = $this->cursoModel->verificarInscripcion($id_curso, $id_estudiante);
            if (!$inscrito) {
                throw new Exception("El estudiante no está inscrito en este curso");
            }

            // Verificar permisos (docente del curso o admin)
            $curso = $this->cursoModel->obtenerCursoPorId($id_curso);
            if ($_SESSION['rol'] === 'docente' && $curso['id_docente'] != $_SESSION['id_usuario']) {
                throw new Exception("No tienes permisos para desinscribir de este curso");
            }

            $resultado = $this->cursoModel->desinscribirEstudiante($id_curso, $id_estudiante);

            if ($resultado) {
                $_SESSION['success'] = "Estudiante desinscrito correctamente";
            } else {
                throw new Exception("Error al desinscribir estudiante");
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header("Location: index.php?action=ver_curso&id=$id_curso");
        exit();
    }
}
?>