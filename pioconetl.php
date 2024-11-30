<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está logueado y pertenece al rol "admin" o rol adecuado
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.html?error=3"); // Redirige al login si no está autorizado
    exit();
}

// Conectar con la base de datos
$conn = new mysqli("localhost", "root", "", "olimpiadas");

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener la retroalimentación de los equipos de la categoría "Pioconetl"
$query = "
    SELECT u.nombre_usuario, e.nombre_equipo, c.retroalimentacion
    FROM calificaciones_p c
    JOIN usuarios u ON c.usuario_id = u.id
    JOIN equipos e ON c.equipo_id = e.id
    JOIN categoria cat ON e.categoria_id = cat.id
    WHERE cat.nombre_categoria = 'Pioconetl'
";
$result = $conn->query($query);

// Verificar si se encontraron resultados
if ($result === false) {
    die("Error en la consulta: " . $conn->error);
}

// Cerrar conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retroalimentación - Pioconetl</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background-color: rgb(226, 187, 47); /* Amarillo */
        }
        .navbar-custom .nav-link {
            color: black; /* Texto oscuro */
        }
        .navbar-custom .nav-link:hover {
            color: white; /* Blanco al pasar el cursor */
        }
    </style>
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Olimpiadas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="login.htm;">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ranking_por_categorias.php">Categorías</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.html">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center">Retroalimentación - Pioconetl</h1>

        <!-- Tabla de retroalimentación -->
        <div class="mb-4">
            <?php if ($result->num_rows > 0) : ?>
                <table class="table table-bordered table-striped">
                    <thead class="table-warning">
                        <tr>
                            <th>Usuario</th>
                            <th>Equipo</th>
                            <th>Retroalimentación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nombre_usuario']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_equipo']); ?></td>
                                <td><?php echo nl2br(htmlspecialchars($row['retroalimentacion'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>No se ha encontrado retroalimentación para la categoría Pioconetl.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
