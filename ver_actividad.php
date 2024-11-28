<?php
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin') {
    header('Location: login.php');  // Redirigir si no es administrador
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "olimpiadas";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consultar las calificaciones de los usuarios
$sql = "SELECT calificaciones.*, usuarios.nombre_usuario, equipos.nombre_equipo, categoria.nombre_categoria 
        FROM calificaciones
        JOIN usuarios ON calificaciones.usuario_id = usuarios.id
        JOIN equipos ON calificaciones.equipo_id = equipos.id
        JOIN categoria ON calificaciones.categoria_id = Categoria.id";
        // ORDER BY calificaciones.fecha DESC";


// Ejecutar la consulta y verificar si se ejecutó correctamente
$result = $conn->query($sql);

// Verificar si la consulta devuelve resultados
if ($result) {
    if ($result->num_rows > 0) {
        // Mostrar los resultados
        while ($row = $result->fetch_assoc()) {
            echo "<p><strong>Usuario:</strong> " . $row['nombre_usuario'] . "<br>";
            echo "<strong>Equipo:</strong> " . $row['nombre_equipo'] . "<br>";
            echo "<strong>Categoría:</strong> " . $row['nombre_categoria'] . "<br>";
            echo "<strong>Calificación:</strong> " . $row['calificacion'] . "<br>";
            echo "<strong>Retroalimentación:</strong> " . $row['retroalimentacion'] . "<br>";
            echo "<hr></p>";
        }
    } else {
        echo "No hay actividad registrada.";
    }
} else {
    // Si la consulta falla
    echo "Error al recuperar los datos: " . $conn->error;
}

$conn->close();
?>
