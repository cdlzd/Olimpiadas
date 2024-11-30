<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');  // Redirigir al login si no está logueado
    exit();
}

// Obtener el ID de la categoría seleccionada
if (!isset($_GET['categoria_id'])) {
    die('Categoría no válida.');
}
$categoria_id = $_GET['categoria_id'];

// Conectar con la base de datos
$conexion = new mysqli("localhost", "root", "", "olimpiadas");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener el nombre del usuario logueado utilizando consulta preparada
$usuario_id = $_SESSION['usuario_id'];
$query_usuario = $conexion->prepare("SELECT nombre_usuario FROM usuarios WHERE id = ?");
$query_usuario->bind_param("i", $usuario_id);  // Usamos 'i' para indicar que es un entero
$query_usuario->execute();
$resultado_usuario = $query_usuario->get_result();
$usuario = $resultado_usuario->fetch_assoc();

// Obtener los equipos de la categoría seleccionada utilizando consulta preparada
$query_equipos = $conexion->prepare("SELECT * FROM equipos WHERE categoria_id = ?");
$query_equipos->bind_param("i", $categoria_id);  // Usamos 'i' para indicar que es un entero
$query_equipos->execute();
$resultado_equipos = $query_equipos->get_result();

// Cerrar la conexión
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipos - Olimpiadas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: #ffc107; /* Amarillo */
        }
        .navbar .btn-dark {
            margin-right: 10px;
        }
        .user-info {
            font-size: 18px;
            margin-right: auto;
        }
        .btn-custom {
            background-color: #eb9501;
            color: white;
            font-size: 18px;
            border-radius: 20px;
        }
        .btn-custom:hover {
            background-color: #d67a00;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-img-top {
            max-height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <nav class="navbar d-flex justify-content-between px-3">
        <div class="user-info">
            <strong><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></strong>
        </div>
        <div>
            <a href="categorias.php" class="btn btn-custom">Categorías</a>
            <a href="login.html" class="btn btn-custom">Cerrar sesión</a>
            <img src="imagenes/logout.jpg" alt="Cerrar sesión" style="width: 30px; margin-left: 10px;">
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Equipos de la categoría</h2>
        <div class="row">
            <?php
            if ($resultado_equipos->num_rows > 0) {
                while ($equipo = $resultado_equipos->fetch_assoc()) {
                    echo '<div class="col-md-4 mb-3">';
                    echo '<div class="card">';
                    echo '<img src="' . htmlspecialchars($equipo['imagen']) . '" class="card-img-top" alt="' . htmlspecialchars($equipo['nombre_equipo']) . '">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($equipo['nombre_equipo']) . '</h5>';
                    echo '<a href="calificar.php?equipo_id=' . $equipo['id'] . '" class="btn btn-primary">Calificar</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No hay equipos disponibles para esta categoría.</p>';
            }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
