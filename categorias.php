<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

// Conexión a la base de datos
$conn = mysqli_connect("localhost", "root", "", "olimpiadas");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Consulta para obtener las categorías
$query_categorias = "SELECT * FROM categoria";
$result_categorias = mysqli_query($conn, $query_categorias);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Categoría</title>
    <!-- Enlace a Bootstrap 4 -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .categoria-card {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .categoria-card:hover {
            transform: scale(1.05);
        }
        .categoria-img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Seleccionar Categoría</h1>

        <!-- Mostrar las categorías -->
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result_categorias)) : ?>
                <div class="col-md-4 mb-4">
                    <div class="categoria-card">
                        <!-- Al seleccionar una categoría, se guarda en la sesión -->
                        <a href="equipos.php?categoria_id=<?php echo $row['id']; ?>">
                            <img src="<?php echo $row['imagen']; ?>" alt="<?php echo htmlspecialchars($row['nombre_categoria']); ?>" class="categoria-img">
                            <h3 class="mt-3"><?php echo htmlspecialchars($row['nombre_categoria']); ?></h3>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Cerrar la conexión
mysqli_close($conn);
?>

