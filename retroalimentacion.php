<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está logueado y pertenece al rol "pollitos"
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'pollitos') {
    header("Location: login.html?error=3"); // Redirige al login si no está autorizado
    exit();
}

// Conectar con la base de datos
$conn = new mysqli("localhost", "root", "", "olimpiadas");

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del usuario logueado y el ID del equipo
$usuario_id = $_SESSION['usuario_id'];
$equipo_id = isset($_GET['equipo_id']) ? $_GET['equipo_id'] : null;

// Verificar que el equipo exista
$query_equipo = "SELECT * FROM equipos WHERE id = ?";
$stmt_equipo = $conn->prepare($query_equipo);
$stmt_equipo->bind_param("i", $equipo_id);
$stmt_equipo->execute();
$result_equipo = $stmt_equipo->get_result();

if ($result_equipo->num_rows == 0) {
    die("Equipo no encontrado.");
}

// Si el formulario es enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $retroalimentacion = $_POST['retroalimentacion'];

    // Insertar retroalimentación en la tabla calificaciones
    $query_insert = "INSERT INTO calificaciones_p (usuario_id, equipo_id, retroalimentacion) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bind_param("iis", $usuario_id, $equipo_id, $retroalimentacion);

    if ($stmt_insert->execute()) {
        echo "<div class='alert alert-success'>Retroalimentación guardada exitosamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al guardar la retroalimentación.</div>";
    }
}

// Cerrar conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retroalimentación</title>
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
                        <a class="nav-link" href="login.html">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="eq_pioconetl.php">Equipos</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center">Retroalimentación del Equipo</h1>

        <!-- Formulario de retroalimentación -->
        <form method="POST">
            <div class="mb-3">
                <label for="retroalimentacion" class="form-label">Retroalimentación:</label>
                <textarea id="retroalimentacion" name="retroalimentacion" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-success w-100">Guardar Retroalimentación</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
