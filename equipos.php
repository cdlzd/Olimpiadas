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
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12 text-end mt-3">
                <span>Bienvenido, <?php echo htmlspecialchars($usuario['nombre_usuario']); ?>!</span>
                <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
            </div>
        </div>
        
        <h2 class="mt-5">Equipos de la Categoría</h2>
        <div class="row">
            <?php
            if ($resultado_equipos->num_rows > 0) {
                while($equipo = $resultado_equipos->fetch_assoc()) {
                    // Mostrar los equipos
                    echo '<div class="col-4 mb-3">';
                    echo '<div class="card">';
                    echo '<img src="' . htmlspecialchars($equipo['imagen']) . '" class="card-img-top" alt="' . htmlspecialchars($equipo['nombre_equipo']) . '">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($equipo['nombre_equipo']) . '</h5>';
                    echo '<a href="calificar.php?equipo_id=' . $equipo['id'] . '" class="btn btn-primary">Calificar</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo 'Categoría seleccionada: ' . $_SESSION['categoria_id'];

                }
            } else {
                echo "No hay equipos disponibles para esta categoría.";
            }
            ?>
        </div>
    </div>
    <button><a href="categorias.php"> Categorias </a></button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
