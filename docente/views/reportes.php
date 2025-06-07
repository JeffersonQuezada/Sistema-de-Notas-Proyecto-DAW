<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generar Reporte</title>
</head>
<body>
    <h2>Generar Reporte</h2>
    <form action="generar_reporte.php" method="POST">
        <label for="id_curso">ID del Curso:</label>
        <input type="number" id="id_curso" name="id_curso" required><br><br>

        <label for="tipo_reporte">Tipo de Reporte:</label>
        <select id="tipo_reporte" name="tipo_reporte" required>
            <option value="pdf">PDF</option>
            <option value="excel">Excel</option>
        </select><br><br>

        <input type="submit" value="Generar Reporte">
    </form>
</body>
</html>