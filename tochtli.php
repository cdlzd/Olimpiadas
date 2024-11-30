<?php
session_start();

// Verificar si el usuario es administrador
if ($_SESSION['rol'] !== 'admin') {
    header("Location: login.html"); // Redirige si no es admin
    exit();
}

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "olimpiadas");

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener los equipos y sus calificaciones en la categoría 'Tochtli'
$query = "
    SELECT 
        e.nombre_equipo, 
        COALESCE(SUM(c.calificacion), 0) AS suma_calificaciones,
        COUNT(c.calificacion) AS num_calificaciones,
        e.portafolio,
        e.video
    FROM equipos e
    LEFT JOIN calificaciones c ON e.id = c.equipo_id
    JOIN categoria cat ON e.categoria_id = cat.id
    WHERE cat.nombre_categoria = 'Tochtli'
    GROUP BY e.id, e.nombre_equipo, e.portafolio, e.video
    ORDER BY (e.portafolio + e.video + COALESCE(SUM(c.calificacion), 0) / NULLIF(COUNT(c.calificacion), 0)) DESC
";

$result = $conn->query($query);

// Verificar si la consulta fue exitosa
if ($result === false) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking de la Categoría Tochtli</title>
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
    <nav class="navbar navbar-light bg-warning px-1">
        <a class="btn btn-dark me-3" href="ver_actividad.php">Ver Actividad de Usuarios</a>
        <a class="btn btn-dark me-3" href="ranking_por_categorias.php">Ver Rankings</a>
        <a class="btn btn-dark me-3" href="dashboard.php">Volver al Dashboard</a>
        <a class="btn btn-dark" href="ranking_por_categorias.php"> Categorias</a>
        <a class="btn btn-dark" href="login.html">Cerrar Sesión</a>
    </nav>
    <div class="container mt-5">
        <h1>Ranking de la Categoría Tochtli</h1>

        <?php
        // Mostrar los resultados si existen
        if ($result->num_rows > 0) {
            echo "<table class='table table-bordered'>
                    <thead>
                        <tr style='background-color: rgb(226, 156, 47)'> >
                            <th>Posición</th>
                            <th>Equipo</th>
                            <th>Portafolio</th>
                            <th>Video</th>
                            <th>Promedio Presentación Oral</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>";
            $position = 1;
            while ($row = $result->fetch_assoc()) {
                // Calcular promedio de presentación oral
                $promedio_presentacion = $row['num_calificaciones'] > 0 
                    ? $row['suma_calificaciones'] / $row['num_calificaciones'] 
                    : 0;

                // Calcular total como suma del portafolio, video y promedio de presentación
                $suma_total = $row['portafolio'] + $row['video'] + $promedio_presentacion;

                echo "<tr style='background-color: rgb(255, 213, 98)'>
                        <td>" . $position++ . "</td>
                        <td>" . htmlspecialchars($row['nombre_equipo']) . "</td>
                        <td>" . number_format($row['portafolio'], 2) . " %</td>
                        <td>" . number_format($row['video'], 2) . " %</td>
                        <td>" . number_format($promedio_presentacion, 2) . " %</td>
                        <td>" . number_format($suma_total, 2) . " %</td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No hay calificaciones disponibles para la categoría Tochtli.</p>";
        }
        ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$conn->close();
?>
