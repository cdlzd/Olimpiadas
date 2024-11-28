<?php
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin') {
    header('Location: login.php');  // Redirigir si no es administrador
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Administrador</title>
</head>
<body>
    <h1>Bienvenido, Administrador</h1>
    <p>Aquí puedes gestionar usuarios y ver actividades.</p>

    <a href="agregar_usuario.php">Agregar Usuario</a> | 
    <a href="ver_actividad.php">Ver Actividad de Usuarios</a> | 
    <a href="ranking_por_categorias.php">Ver rankings</a> |
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
