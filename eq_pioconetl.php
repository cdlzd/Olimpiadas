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

// Obtener los equipos de la categoría Pioconetl
$query = "SELECT * FROM equipos WHERE categoria_id = '5'";
$result = $conn->query($query);

// Verificar si se encontraron equipos
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
    <title>Equipos - Pioconetl</title>
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
        .team-card {
            margin-bottom: 15px;
        }
        .team-image {
            max-height: 150px;
            object-fit: cover;
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
                        <a class="nav-link" href="login.html">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center">Equipos - Pioconetl</h1>

        <div class="row justify-content-center mt-4">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($equipo = $result->fetch_assoc()): ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card team-card">
                            <img src="<?php echo htmlspecialchars($equipo['imagen']); ?>" alt="Imagen del equipo" class="card-img-top team-image">
                            <div class="card-body">
                                <h5 class="card-title text-center"><?php echo htmlspecialchars($equipo['nombre_equipo']); ?></h5>
                                <a href="retroalimentacion.php?equipo_id=<?php echo $equipo['id']; ?>" class="btn btn-dark w-100">Retroalimentación</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No hay equipos disponibles en la categoría Pioconetl.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
