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
    <link rel="stylesheet" href="dash.css">
    <title>Dashboard - Administrador</title>
</head>
<body>
    <h1>Bienvenido, Administrador</h1>
    <p>Aquí puedes gestionar usuarios y ver actividades.</p>
    <div>
        <a style="margin-top: 20px; margin-left:3%; background-color: rgb(226, 187, 47); borde-radius:20px; color:black; width:12%; height:11%" 
        class="btn btn-danger" href="agregar_usuario.php">Agregar Usuario</a>
        <a style="margin-top: 20px; margin-left:21%; background-color: rgb(226, 187, 47); color:black; width:16%; height:11%" 
        class="btn btn-danger" href="ver_actividad.php">Ver Actividad<br> de Usuarios</a>
        <a style="margin-top: 20px; margin-left:41%; background-color: rgb(226, 187, 47); color:black; width:10%; height:11%" 
        class="btn btn-danger" href="ranking_por_categorias.php">Ver rankings</a>
        <a style="margin-top: 20px; margin-left:56%; background-color: rgb(226, 187, 47); color:black; width:16%; height:10%" 
        class="btn btn-danger" href="conjunto.php">Visualizar calificaciones</a>
        <a style="margin-top: 20px; margin-left:78%; background-color: rgb(226, 187, 47); color:black; width:10%; height:10%" 
        class="btn btn-danger" href="login.html">Cerrar Sesión</a>
    </div>
</body>
</html>
