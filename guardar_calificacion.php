<?php
// Conexión a la base de datos
$conn = mysqli_connect("localhost", "root", "", "olimpiadas");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Verificar si el formulario se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $categoria_id = $_POST['categoria_id'];
    $equipo_id = $_POST['equipo_id'];
    $claridad = $_POST['claridad'];
    $dominio = $_POST['dominio'];
    $escalabilidad = $_POST['escalabilidad'];
    $beneficiarios = $_POST['beneficiarios'];
    $retroalimentacion = $_POST['retroalimentacion'];

    // Verificar si los datos están completos
    if (
        empty($claridad) || empty($dominio) || empty($escalabilidad) || 
        empty($beneficiarios) || empty($retroalimentacion)
    ) {
        die('Faltan datos.');
    }

    // Validar las calificaciones (rango de 1 a 5)
    if (
        !is_numeric($claridad) || $claridad < 1 || $claridad > 5 ||
        !is_numeric($dominio) || $dominio < 1 || $dominio > 5 ||
        !is_numeric($escalabilidad) || $escalabilidad < 1 || $escalabilidad > 5 ||
        !is_numeric($beneficiarios) || $beneficiarios < 1 || $beneficiarios > 5
    ) {
        die('Las calificaciones deben estar entre 1 y 5.');
    }

    // Realizar las conversiones a porcentajes
    $claridad_porcentaje = $claridad * (7 / 5);        // 7%
    $dominio_porcentaje = $dominio * (5 / 5);          // 5%
    $escalabilidad_porcentaje = $escalabilidad * (4 / 5); // 4%
    $beneficiarios_porcentaje = $beneficiarios * (4 / 5); // 4%

    // Calcular el porcentaje total
    $calificacion_total = $claridad_porcentaje + $dominio_porcentaje + $escalabilidad_porcentaje + $beneficiarios_porcentaje;

    // Obtener el ID del usuario desde la sesión
    session_start();
    $usuario_id = $_SESSION['usuario_id'];

    // Preparar la consulta para insertar la calificación
    $query = "INSERT INTO calificaciones (usuario_id, equipo_id, categoria_id, calificacion, retroalimentacion) 
              VALUES (?, ?, ?, ?, ?)";

    // Usar consultas preparadas para evitar inyecciones SQL
    if ($stmt = $conn->prepare($query)) {
        // Vincular los parámetros
        $stmt->bind_param("iiids", $usuario_id, $equipo_id, $categoria_id, $calificacion_total, $retroalimentacion);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "Calificación guardada correctamente.";
        } else {
            echo "Error al guardar la calificación: " . $stmt->error;
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificación Guardada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>¡Gracias por calificar!</h1>
        <p>La calificación para el equipo ha sido guardada correctamente.</p>

        <!-- Botón para ir a la página de calificaciones -->
        <a href="calificaciones.php" class="btn btn-primary">Ver mis calificaciones</a>

        <!-- Botón para volver a calificar otro equipo -->
        <a href="calificar.php?equipo_id=<?php echo $equipo_id; ?>" class="btn btn-secondary">Calificar otro equipo</a>
        
        <!-- Botón para elegir otra categoría -->
        <a href="equipos.php?categoria_id=<?php echo $categoria_id; ?>" class="btn btn-secondary">Elegir otra categoría</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

