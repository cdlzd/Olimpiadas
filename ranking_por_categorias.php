<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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

    <!-- Contenido principal -->
    <div class="container content">
        <h1 class="text-center mb-4">Categorías</h1>
        <div class="row justify-content-center">
            <?php
            // Conexión a la base de datos
            $conn = new mysqli("localhost", "root", "", "olimpiadas");

            // Verificar la conexión
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            // Obtener las categorías excluyendo "pioconetl"
            $query = "SELECT * FROM categoria WHERE nombre_categoria != 'pioconetl'";
            $result = $conn->query($query);

            // Verificar si la consulta fue exitosa
            if ($result === false) {
                die("Error en la consulta: " . $conn->error);
            }

            // Verificar si hay categorías disponibles
            if ($result->num_rows > 0) {
                // Mostrar las categorías con enlaces e imágenes
                while ($categoria = $result->fetch_assoc()) {
                    $categoria_nombre = htmlspecialchars($categoria['nombre_categoria']);
                    $pagina_categoria = strtolower($categoria_nombre) . ".php"; // Convertir el nombre a minúsculas y agregar ".php"
                    $imagen_categoria = htmlspecialchars($categoria['imagen']); // Ruta de la imagen

                    // Crear un card para cada categoría
                    echo "
                    <div class='col-md-3 col-sm-6 text-center mb-4'>
                        <a href='$pagina_categoria' style='text-decoration: none; color: inherit;'>
                            <div class='card'>
                                <img src='$imagen_categoria' alt='$categoria_nombre' class='card-img-top' style='height: 150px; object-fit: cover;'>
                                <div class='card-body'>
                                    <h5 class='card-title'>$categoria_nombre</h5>
                                </div>
                            </div>
                        </a>
                    </div>";
                }
            } else {
                echo "<p class='text-center'>No hay categorías disponibles.</p>";
            }

            // Mostrar la categoría Pioconetl al final
            echo "
            <div class='col-md-3 col-sm-6 text-center mb-4'>
                <a href='pioconetl.php' style='text-decoration: none; color: inherit;'>
                    <div class='card'>
                        <img src='imagenes/Pioconetl_icono.png' alt='Pioconetl' class='card-img-top' style='height: 150px; object-fit: cover;'>
                        <div class='card-body'>
                            <h5 class='card-title'>Pioconetl</h5>
                        </div>
                    </div>
                </a>
            </div>";

            // Cerrar la conexión
            $conn->close();
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
