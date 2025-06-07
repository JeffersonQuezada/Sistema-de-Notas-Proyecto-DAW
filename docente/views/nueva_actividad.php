<h2>Nueva Actividad</h2>
<form action="actividades.php?accion=guardar" method="POST">
    <input type="hidden" name="id_docente" value="1"> <!-- Aquí irá el id de sesión -->
    <label>Título:</label>
    <input type="text" name="titulo" required><br>

    <label>Descripción:</label>
    <textarea name="descripcion" required></textarea><br>

    <label>Fecha de Entrega:</label>
    <input type="date" name="fecha_entrega" required><br>

    <label>Grupo:</label>
    <input type="number" name="id_grupo" required><br>

    <input type="submit" value="Guardar">
</form>
