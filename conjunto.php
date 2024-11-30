<?php
// Conectar a la base de datos
$conn = mysqli_connect("localhost", "root", "", "olimpiadas");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Consulta para obtener los datos necesarios
$query = "
    SELECT 
        e.nombre_equipo,
        e.portafolio,
        e.video,
        cat.nombre_categoria,
        COALESCE(SUM(c.calificacion), 0) AS suma_calificaciones,
        (e.portafolio + e.video + COALESCE(SUM(c.calificacion), 0)) AS suma_total
    FROM equipos e
    JOIN categoria cat ON e.categoria_id = cat.id
    LEFT JOIN calificaciones c ON e.id = c.equipo_id
    GROUP BY e.id, cat.nombre_categoria;
";
$result = mysqli_query($conn, $query);

// Cerrar la conexión
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dash.css">
    <title>Conjunto de Equipos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
        <a style="margin-top: 20px; margin-left:3%; background-color: rgb(226, 187, 47); color:black; width:16%; height:11%" 
        class="btn btn-danger" href="ver_actividad.php">Ver Actividad<br> de Usuarios</a>
        <a style="margin-top: 20px; margin-left:25%; background-color: rgb(226, 187, 47); color:black; width:10%; height:11%" 
        class="btn btn-danger" href="ranking_por_categorias.php">Ver rankings</a>
        <a style="margin-top: 20px; margin-left:41%; background-color: rgb(226, 187, 47); color:black; width:16%; height:11%" 
        class="btn btn-danger" href="dashboard.php">Volver a dashboard</a>
        <a style="margin-top: 20px; margin-left:63%; background-color: rgb(226, 187, 47); color:black; width:10%; height:10%" 
        class="btn btn-danger" href="login.html">Cerrar Sesión</a>
    <div style="position:absolute; top: 60px" class="container mt-5">
        <h1>Información de Equipos</h1>
        <table class="table table-bordered">
            <thead>
                <tr style="background-color: rgb(260, 183, 27)">
                    <th>Categoría</th>
                    <th>Equipo</th>
                    <th>Portafolio</th>
                    <th>Video</th>
                    <th>Suma de Calificaciones</th>
                    <th>Suma Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($equipo = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($equipo['nombre_categoria']); ?></td>
                        <td><?php echo htmlspecialchars($equipo['nombre_equipo']); ?></td>
                        <td><?php echo $equipo['portafolio']; ?></td>
                        <td><?php echo $equipo['video']; ?></td>
                        <td><?php echo $equipo['suma_calificaciones']; ?></td>
                        <td><?php echo $equipo['suma_total']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
