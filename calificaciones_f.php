<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');  // Redirigir al login si no está logueado
    exit();
}

// Conectar con la base de datos
$conn = mysqli_connect("localhost", "root", "", "olimpiadas");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Obtener el ID del usuario logueado
$usuario_id = $_SESSION['usuario_id'];

// Obtener las categorías disponibles
$query_categorias = "SELECT * FROM categoria";
$result_categorias = mysqli_query($conn, $query_categorias);

// Mostrar las calificaciones del usuario para cada equipo en cada categoría
$query_calificaciones = "
    SELECT c.*, e.nombre_equipo, cat.nombre_categoria
    FROM calificaciones c
    JOIN equipos e ON c.equipo_id = e.id
    JOIN categoria cat ON e.categoria_id = cat.id
    WHERE c.usuario_id = ?
";
$stmt_calificaciones = $conn->prepare($query_calificaciones);
$stmt_calificaciones->bind_param("i", $usuario_id);
$stmt_calificaciones->execute();
$result_calificaciones = $stmt_calificaciones->get_result();

// Obtener los equipos sin calificar por el usuario
$query_equipos_no_calificados = "
    SELECT e.*, cat.nombre_categoria
    FROM equipos e
    JOIN categoria cat ON e.categoria_id = cat.id
    LEFT JOIN calificaciones c ON e.id = c.equipo_id AND c.usuario_id = ?
    WHERE c.id IS NULL
";
$stmt_no_calificados = $conn->prepare($query_equipos_no_calificados);
$stmt_no_calificados->bind_param("i", $usuario_id);
$stmt_no_calificados->execute();
$result_no_calificados = $stmt_no_calificados->get_result();

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Calificaciones - Olimpiadas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Olimpiadas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="login.html">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categorias.php">Categorías</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.html">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Mis Calificaciones</h1>
        <br>

        <!-- Equipos Calificados -->
        <div class="mb-4">
            <h3>Calificaciones dadas</h3>
            <br>
            <?php if ($result_calificaciones->num_rows > 0) : ?>
                <table class="table table-bordered">
                    <thead>
                        <tr class="table-warning">
                            <th>Equipo</th>
                            <th>Categoría</th>
                            <th>Calificación</th>
                            <th>Retroalimentación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($calificacion = $result_calificaciones->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($calificacion['nombre_equipo']); ?></td>
                                <td><?php echo htmlspecialchars($calificacion['nombre_categoria']); ?></td>
                                <td><?php echo number_format($calificacion['calificacion'], 2); ?> %</td>
                                <td><?php echo htmlspecialchars($calificacion['retroalimentacion']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>No has calificado ningún equipo aún.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>