<?php
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin') {
    header('Location: login.php');  // Redirigir si no es administrador
    exit();
}

// Conexión a la base de datos
$conn = mysqli_connect("localhost", "root", "", "olimpiadas");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST['nombre_usuario'];
    $correo_usuario = $_POST['correo_usuario'];
    $contrasena_usuario = password_hash($_POST['contrasena_usuario'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    // Verificar si los datos están completos
    if (empty($nombre_usuario) || empty($correo_usuario) || empty($contrasena_usuario) || empty($rol)) {
        die('Faltan datos.');
    }

    // Insertar el nuevo usuario en la base de datos
    $query = "INSERT INTO usuarios (nombre_usuario, correo_usuario, contrasena_usuario, rol) 
              VALUES (?, ?, ?, ?)";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ssss", $nombre_usuario, $correo_usuario, $contrasena_usuario, $rol);

        if ($stmt->execute()) {
            echo "Usuario agregado correctamente.";
        } else {
            echo "Error al agregar el usuario: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Agregar Usuario</h1>

        <form action="agregar_usuario.php" method="POST">
            <div class="form-group">
                <label for="nombre_usuario">Nombre de Usuario</label>
                <input type="text" name="nombre_usuario" id="nombre_usuario" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="correo_usuario">Correo de Usuario</label>
                <input type="email" name="correo_usuario" id="correo_usuario" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="contrasena_usuario">Contraseña</label>
                <input type="password" name="contrasena_usuario" id="contrasena_usuario" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="rol">Rol</label>
                <select name="rol" id="rol" class="form-control" required>
                    <option value="usuario">Usuario</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Agregar Usuario</button>
        </form>

        <br>
        <a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
