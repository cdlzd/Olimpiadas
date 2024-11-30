<?php
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin') {
    header('Location: login.php'); // Redirigir si no es administrador
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "olimpiadas";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se envió una solicitud para eliminar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $id_calificacion = intval($_POST['calificacion_id']); // Validar el ID

    // Eliminar la calificación
    $delete_sql = "DELETE FROM calificaciones WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $id_calificacion);

    if ($stmt->execute()) {
        echo "<script>alert('Actividad eliminada exitosamente');</script>";
    } else {
        echo "<script>alert('Error al eliminar la actividad');</script>";
    }

    $stmt->close();
}

// Consultar las calificaciones de los usuarios
$sql = "
    SELECT calificaciones.id AS calificacion_id, calificaciones.*, usuarios.nombre_usuario, equipos.nombre_equipo, categoria.nombre_categoria 
    FROM calificaciones
    JOIN usuarios ON calificaciones.usuario_id = usuarios.id
    JOIN equipos ON calificaciones.equipo_id = equipos.id
    JOIN categoria ON calificaciones.categoria_id = categoria.id
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Actividad de Usuarios</title>
    <style>
        body {
            margin-top: 60px;
        }
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        .content {
            margin-top: 80px;
        }
    </style>
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-light bg-warning px-3">
        <a class="btn btn-dark me-3" href="ver_actividad.php">Ver Actividad de Usuarios</a>
        <a class="btn btn-dark me-3" href="ranking_por_categorias.php">Ver Rankings</a>
        <a class="btn btn-dark me-3" href="dashboard.php">Volver al Dashboard</a>
        <a class="btn btn-dark" href="login.html">Cerrar Sesión</a>
    </nav>

    <!-- Contenido dinámico -->
    <div class="container content">
        <h1>Actividad de Usuarios</h1>
        <hr>
        <?php if ($result && $result->num_rows > 0): ?>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Equipo</th>
                        <th>Categoría</th>
                        <th>Calificación</th>
                        <th>Retroalimentación</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nombre_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_equipo']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_categoria']); ?></td>
                            <td><?php echo htmlspecialchars($row['calificacion']); ?></td>
                            <td><?php echo htmlspecialchars($row['retroalimentacion']); ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta actividad?');">
                                    <input type="hidden" name="calificacion_id" value="<?php echo $row['calificacion_id']; ?>">
                                    <button type="submit" name="eliminar" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay actividad registrada.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Cerrar conexión
$conn->close();
?>
