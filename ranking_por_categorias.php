<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "olimpiadas");

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener las categorías desde la base de datos
$query = "SELECT * FROM categoria"; // Asegúrate de que el nombre de la tabla sea correcto (¿"categorias"?)
$result = $conn->query($query);

// Verificar si la consulta fue exitosa
if ($result === false) {
    die("Error en la consulta: " . $conn->error);
}

// Verificar si hay categorías disponibles
if ($result->num_rows > 0) {
    // Mostrar las categorías con enlaces
    while ($categoria = $result->fetch_assoc()) {
        // Asignar un enlace basado en el nombre de la categoría
        $categoria_nombre = $categoria['nombre_categoria'];
        $pagina_categoria = strtolower($categoria_nombre) . ".php";  // Convertir el nombre de la categoría a minúsculas y agregar ".php"
        
        // Mostrar el enlace
        echo "<p><a href='$pagina_categoria'>$categoria_nombre</a></p>";
    }
} else {
    echo "No hay categorías disponibles.";
}

// Cerrar la conexión
$conn->close();
?>
