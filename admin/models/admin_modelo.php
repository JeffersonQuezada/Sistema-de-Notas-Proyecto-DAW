<?php
include __DIR__ . '/../../includes/conexion.php';
/* 📊 DASHBOARD */
function obtenerEstadisticasDashboard() {
    global $conexion;
    $estadisticas = [];

    $result = $conexion->query("SELECT COUNT(*) AS total FROM usuarios");
    $estadisticas['usuarios'] = $result->fetch_assoc()['total'];

    $result = $conexion->query("SELECT COUNT(*) AS total FROM cursos");
    $estadisticas['cursos'] = $result->fetch_assoc()['total'];

    $result = $conexion->query("SELECT COUNT(*) AS total FROM entregas");
    $estadisticas['entregas'] = $result->fetch_assoc()['total'];

    $result = $conexion->query("SELECT COUNT(*) AS total FROM notificaciones");
    $estadisticas['notificaciones'] = $result->fetch_assoc()['total'];

    return $estadisticas;
}

/* 👥 USUARIOS */
function obtenerUsuarios() {
    global $conexion;
    $resultado = $conexion->query("SELECT * FROM usuarios");
    return $resultado->fetch_all(MYSQLI_ASSOC);
}

function obtenerUsuarioPorId($id) {
    global $conexion;
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function insertarUsuario($datos) {
    global $conexion;
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)");
    $contrasenaHash = password_hash($datos['contrasena'], PASSWORD_DEFAULT);
    $stmt->bind_param("ssss", $datos['nombre'], $datos['correo'], $contrasenaHash, $datos['rol']);
    $stmt->execute();
}

function actualizarUsuarioPorId($id, $datos) {
    global $conexion;
    if (!empty($datos['contrasena'])) {
        $contrasenaHash = password_hash($datos['contrasena'], PASSWORD_DEFAULT);
        $stmt = $conexion->prepare("UPDATE usuarios SET nombre = ?, correo = ?, contrasena = ?, rol = ? WHERE id_usuario = ?");
        $stmt->bind_param("ssssi", $datos['nombre'], $datos['correo'], $contrasenaHash, $datos['rol'], $id);
    } else {
        $stmt = $conexion->prepare("UPDATE usuarios SET nombre = ?, correo = ?, rol = ? WHERE id_usuario = ?");
        $stmt->bind_param("sssi", $datos['nombre'], $datos['correo'], $datos['rol'], $id);
    }
    $stmt->execute();
}

function eliminarUsuarioPorId($id) {
    global $conexion;
    $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

/* 📚 CURSOS */
function obtenerCursos() {
    global $conexion;
    $resultado = $conexion->query("SELECT cursos.*, usuarios.nombre AS docente 
        FROM cursos INNER JOIN usuarios ON cursos.id_docente = usuarios.id_usuario");
    return $resultado->fetch_all(MYSQLI_ASSOC);
}

function obtenerCursoPorId($id) {
    global $conexion;
    $stmt = $conexion->prepare("SELECT * FROM cursos WHERE id_curso = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function insertarCurso($datos) {
    global $conexion;
    $stmt = $conexion->prepare("INSERT INTO cursos (nombre_curso, descripcion, id_docente, contrasena, capacidad, grupo) VALUES (?, ?, ?, ?, ?, ?)");
    $contrasenaHash = password_hash($datos['contrasena'], PASSWORD_DEFAULT);
    $stmt->bind_param("ssisis", $datos['nombre_curso'], $datos['descripcion'], $datos['id_docente'], $contrasenaHash, $datos['capacidad'], $datos['grupo']);
    $stmt->execute();
}

function actualizarCursoPorId($id, $datos) {
    global $conexion;
    if (!empty($datos['contrasena'])) {
        $contrasenaHash = password_hash($datos['contrasena'], PASSWORD_DEFAULT);
        $stmt = $conexion->prepare("UPDATE cursos SET nombre_curso = ?, descripcion = ?, id_docente = ?, contrasena = ?, capacidad = ?, grupo = ? WHERE id_curso = ?");
        $stmt->bind_param("ssisisi", $datos['nombre_curso'], $datos['descripcion'], $datos['id_docente'], $contrasenaHash, $datos['capacidad'], $datos['grupo'], $id);
    } else {
        $stmt = $conexion->prepare("UPDATE cursos SET nombre_curso = ?, descripcion = ?, id_docente = ?, capacidad = ?, grupo = ? WHERE id_curso = ?");
        $stmt->bind_param("ssisii", $datos['nombre_curso'], $datos['descripcion'], $datos['id_docente'], $datos['capacidad'], $datos['grupo'], $id);
    }
    $stmt->execute();
}

function eliminarCursoPorId($id) {
    global $conexion;
    $stmt = $conexion->prepare("DELETE FROM cursos WHERE id_curso = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

function obtenerDocentes() {
    global $conexion;
    $resultado = $conexion->query("SELECT * FROM usuarios WHERE rol = 'docente'");
    return $resultado->fetch_all(MYSQLI_ASSOC);
}

/* 📝 NOTAS */
function obtenerNotasConEstudiantesCursos() {
    global $conexion;
    $resultado = $conexion->query("SELECT notas.*, u.nombre AS estudiante, c.nombre_curso, a.nombre AS actividad
        FROM notas 
        INNER JOIN usuarios u ON notas.id_estudiante = u.id_usuario
        INNER JOIN cursos c ON notas.id_curso = c.id_curso
        INNER JOIN actividades a ON notas.id_actividad = a.id_actividad");
    return $resultado->fetch_all(MYSQLI_ASSOC);
}

/* 📅 ASISTENCIAS */
function obtenerAsistencias() {
    global $conexion;
    $resultado = $conexion->query("SELECT asistencias.*, u.nombre AS estudiante, c.nombre_curso
        FROM asistencias 
        INNER JOIN usuarios u ON asistencias.id_estudiante = u.id_usuario
        INNER JOIN cursos c ON asistencias.id_curso = c.id_curso");
    return $resultado->fetch_all(MYSQLI_ASSOC);
}

/* 🎖️ INSIGNIAS */
function obtenerInsignias() {
    global $conexion;
    $resultado = $conexion->query("SELECT * FROM insignias");
    return $resultado->fetch_all(MYSQLI_ASSOC);
}

/* 🎯 MISIONES */
function obtenerMisiones() {
    global $conexion;
    $resultado = $conexion->query("SELECT * FROM misiones");
    return $resultado->fetch_all(MYSQLI_ASSOC);
}

/* 📢 NOTIFICACIONES */
function obtenerNotificacionesAdmin() {
    global $conexion;
    $resultado = $conexion->query("SELECT notificaciones.*, u.nombre AS usuario 
        FROM notificaciones 
        INNER JOIN usuarios u ON notificaciones.id_usuario = u.id_usuario
        ORDER BY fecha DESC");
    return $resultado->fetch_all(MYSQLI_ASSOC);
}
