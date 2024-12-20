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
    <div class="container mt-5">
        <h1>Mis Calificaciones</h1>
        <br>
        <div class="mb-4">
            <h3>Calificaciones dadas</h3>
            <br>
            <?php if ($result_calificaciones->num_rows > 0) : ?>
                <table class="table table-bordered">
                    <thead>
                        <tr style="background-color: rgb(260, 183, 27)">
                            <th>Equipo</th>
                            <th>Categoría</th>
                            <th>Calificación</th>
                            <th>Retroalimentación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($calificacion = $result_calificaciones->fetch_assoc()) : ?>
                            <tr style="background-color: rgb(260, 283, 27)">
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

        <div class="mb-4">
            <h2>Equipos sin calificación</h2>
            <?php if ($result_no_calificados->num_rows > 0) : ?>
                <table class="table table-bordered">
                    <thead>
                        <tr style="background-color: rgb(88, 174, 249)">
                            <th>Equipo</th>
                            <th>Categoría</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($equipo = $result_no_calificados->fetch_assoc()) : ?>
                            <tr style="background-color: rgb(179, 206, 246)">
                                <td ><?php echo htmlspecialchars($equipo['nombre_equipo']); ?></td>
                                <td ><?php echo htmlspecialchars($equipo['nombre_categoria']); ?></td>
                                <td>
                                    <a href="calificar.php?equipo_id=<?php echo $equipo['id']; ?>" class="btn btn-primary">Calificar</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>Has calificado todos los equipos.</p>
            <?php endif; ?>
        </div>

        <div class="d-flex justify-content-between">
           <button style=" position:absolute;  margin: 10px; right:20px; border-radius:20px; background-color: rgb(246, 133, 27); text-aling=center; height:15%; width:22%; "> 
            <a style=" font-size:20px; color:white;" href="categorias.php"> Seleccionar otra categoria</a> </button>
                <br>
           <!--<a href="equipos.php?categoria_id=<?php echo $_GET['categoria_id']; ?>" class="btn btn-secondary">Elegir otra categoría</a>
            <a href="calificar.php?equipo_id=<?php echo $equipo['equipo_id']; ?>" class="btn btn-success">Calificar otro equipo</a>
            -->
        </div>
    </div> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
