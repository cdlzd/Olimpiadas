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

// Consulta para obtener los equipos y sus calificaciones en la categoría 'Axolotl'
$query = "
    SELECT e.nombre_equipo, SUM(c.calificacion) AS suma_calificaciones
    FROM equipos e
    JOIN calificaciones c ON e.id = c.equipo_id
    JOIN categoria cat ON e.categoria_id = cat.id
    WHERE cat.nombre_categoria = 'Axolotl'
    GROUP BY e.id
    ORDER BY suma_calificaciones DESC
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
    <title>Ranking de la Categoría Axolotl</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Ranking de la Categoría Axolotl</h1>

        <?php
        // Mostrar los resultados si existen
        if ($result->num_rows > 0) {
            echo "<table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>Posición</th>
                            <th>Equipo</th>
                            <th>Suma de Calificaciones</th>
                        </tr>
                    </thead>
                    <tbody>";
            $position = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $position++ . "</td>
                        <td>" . $row['nombre_equipo'] . "</td>
                        <td>" . $row['suma_calificaciones'] . "</td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No hay calificaciones disponibles para la categoría Axolotl.</p>";
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
