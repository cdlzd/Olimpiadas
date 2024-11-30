<?php
session_start(); // Iniciar sesión para guardar los datos del usuario

// Conexión a la base de datos
$servername = "localhost"; // Cambiar si es necesario
$username = "root"; // Cambiar por tu usuario
$password = ""; // Cambiar por tu contraseña
$dbname = "olimpiadas"; // Nombre de la base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = $_POST['contrasena'];

    // Preparar la consulta SQL para verificar las credenciales
    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nombre_usuario); // "s" significa que es un string
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Verificar si el usuario existe
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        
        // Verificar la contraseña
        if ($contrasena == $usuario['contrasena']) { // Comparación sin hash (mejor usar hash en producción)
            // Crear la sesión del usuario
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
            $_SESSION['rol'] = $usuario['rol']; // Almacenar el rol

            // Redirigir según el rol del usuario
            if ($usuario['rol'] == 'admin') {
                header("Location: dashboard.php");
            } elseif ($usuario['rol'] == 'usuario') {
                header("Location: categorias.php");
            } elseif ($usuario['rol'] == 'pollitos') {
                header("Location: eq_pioconetl.php");
            } else {
                // Rol no reconocido
                header("Location: login.html?error=2"); // Error específico para rol desconocido
            }
            exit();
        } else {
            // Contraseña incorrecta
            header("Location: login.html?error=1"); // Error de credenciales
            exit();
        }
    } else {
        // Usuario no encontrado
        header("Location: login.html?error=1"); // Error de credenciales
        exit();
    }
}

$conn->close();
?>
