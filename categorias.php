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

// Consulta para obtener las categorías, excluyendo "Pioconetl"
$query_categorias = "SELECT * FROM categoria WHERE nombre_categoria != 'Pioconetl'";
$result_categorias = mysqli_query($conn, $query_categorias);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Categoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: #ffc107; /* Amarillo */
        }
        .navbar .btn-custom {
            background-color: #eb9501;
            color: white;
            font-size: 18px;
            border-radius: 20px;
        }
        .navbar .btn-custom:hover {
            background-color: #d67a00;
        }
        .categoria-card {
            width: 200px;
            margin: 15px;
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            border-radius: 20px;
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
    <!-- Barra de navegación -->
    <nav class="navbar d-flex justify-content-between px-3">
        <span class="navbar-text"><strong>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></strong></span>
        <div>
            <a href="calificaciones_f.php" class="btn btn-custom">Calificaciones</a>
            <a href="login.html" class="btn btn-custom">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Seleccionar Categoría</h1>
        <!-- Mostrar las categorías en una fila horizontal -->
        <div class="d-flex flex-wrap justify-content-center">
            <?php while ($row = mysqli_fetch_assoc($result_categorias)) : ?>
                <div class="categoria-card">
                    <!-- Al seleccionar una categoría, se guarda en la sesión -->
                    <a href="equipos.php?categoria_id=<?php echo $row['id']; ?>">
                        <img src="<?php echo htmlspecialchars($row['imagen']); ?>" alt="<?php echo htmlspecialchars($row['nombre_categoria']); ?>" class="categoria-img">
                        <h3 class="mt-3"><?php echo htmlspecialchars($row['nombre_categoria']); ?></h3>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Cerrar la conexión
mysqli_close($conn);
?>
