<?php
session_start();

// Verificar si el usuario es administrador
if ($_SESSION['rol'] != 'admin') {
    header("Location: login.php"); // Redirigir a login si no es admin
    exit();
}

// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "olimpiadas";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID de la categoría desde la URL
if (isset($_GET['categoria_id'])) {
    $categoria_id = $_GET['categoria_id'];

    // Obtener los equipos de esta categoría junto con la sumatoria de sus calificaciones
    $sql = "SELECT e.nombre_equipo, SUM(c.calificacion) AS total_calificacion 
            FROM equipos e
            LEFT JOIN calificaciones c ON e.id = c.equipo_id
            WHERE e.categoria_id = ?
            GROUP BY e.id
            ORDER BY total_calificacion DESC";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $categoria_id);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<h1>Ranking de Equipos - Categoría: ";

        // Obtener el nombre de la categoría
        $categoria_sql = "SELECT nombre_categoria FROM categorias WHERE id = ?";
        $stmt_categoria = $conn->prepare($categoria_sql);
        $stmt_categoria->bind_param("i", $categoria_id);
        $stmt_categoria->execute();
        $resultado_categoria = $stmt_categoria->get_result();
        if ($categoria = $resultado_categoria->fetch_assoc()) {
            echo $categoria['nombre_categoria'];
        }

        echo "</h1>";

        // Mostrar el ranking
        if ($result->num_rows > 0) {
            echo "<table class='table'>";
            echo "<thead><tr><th>Equipo</th><th>Total de Calificación</th></tr></thead>";
            echo "<tbody>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row['nombre_equipo'] . "</td><td>" . $row['total_calificacion'] . "</td></tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "No hay calificaciones para esta categoría.";
        }
    } else {
        echo "Error al recuperar los datos.";
    }

    $stmt->close();
} else {
    echo "Categoría no válida.";
}

$conn->close();
?>
