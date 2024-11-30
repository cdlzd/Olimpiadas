<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');  // Redirigir al login si no está logueado
    exit();
}

// Obtener el ID del equipo desde la URL
if (!isset($_GET['equipo_id'])) {
    die('Equipo no válido.');
}
$equipo_id = $_GET['equipo_id'];

// Conectar con la base de datos
$conexion = new mysqli("localhost", "root", "", "olimpiadas");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener el equipo seleccionado
$query_equipo = $conexion->prepare("SELECT * FROM equipos WHERE id = ?");
$query_equipo->bind_param("i", $equipo_id);
$query_equipo->execute();
$resultado_equipo = $query_equipo->get_result();
$equipo = $resultado_equipo->fetch_assoc();

// Verificar si se encontró el equipo
if (!$equipo) {
    die('Equipo no encontrado.');
}

// Obtener el nombre del usuario logueado
$usuario_id = $_SESSION['usuario_id'];
$query_usuario = $conexion->prepare("SELECT nombre_usuario FROM usuarios WHERE id = ?");
$query_usuario->bind_param("i", $usuario_id);
$query_usuario->execute();
$resultado_usuario = $query_usuario->get_result();
$usuario = $resultado_usuario->fetch_assoc();

// Guardar la categoría seleccionada en la sesión
$_SESSION['categoria_id'] = $equipo['categoria_id'];  // Guardamos el ID de la categoría de este equipo

// Cerrar la conexión
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificar Equipo - Olimpiadas</title>
    <link rel="stylesheet" href="calificar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: #ffc107; /* Amarillo */
        }
        .navbar .btn-custom {
            background-color: #eb9501;
            color: white;
            font-size: 18px;
            border-radius: 20px;
        }
        .navbar .btn-custom:hover {
            background-color: #d67a00;
        }
        .contenedor label {
            font-weight: bold;
        }
        .titulo {
            font-weight: bold;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg d-flex justify-content-between px-3">
        <div>
            <a href="login.html" class="btn btn-custom">Inicio</a>
            <a href="categorias.php" class="btn btn-custom">Categorías</a>
            <a href="equipos.php?categoria_id=<?php echo $equipo['categoria_id']; ?>" class="btn btn-custom">Equipos</a>
            <a href="calificaciones_f.php" class="btn btn-custom">Calificaciones</a>
        </div>
        <a href="login.html" class="btn btn-custom">Cerrar Sesión</a>
    </nav>
    <div class="container mt-5">
        <h1 class="titulo">Calificar Equipo: <?php echo htmlspecialchars($equipo['nombre_equipo']); ?></h1>
        
        <form action="guardar_calificacion.php" method="POST">
            <!-- Enviar el ID del equipo seleccionado -->
            <input type="hidden" name="equipo_id" value="<?php echo $equipo['id']; ?>">
            <!-- Enviar también el ID de la categoría -->
            <input type="hidden" name="categoria_id" value="<?php echo $equipo['categoria_id']; ?>">

            <!-- Criterios de Calificación -->
            <div class="form-group">
                <label  for="claridad" class="contenedor">
                    <div class="title"> <strong>Claridad y Coherencia (1-5) </strong></div>
                    <br> 
                    <strong>Excelente (4-5 pts)</strong>	
                    La presentación es clara, bien estructurada y los puntos principales se desarrollan de manera lógica y coherente.	
                    <br>
                    <strong>Bueno (3-4 pts)</strong>
                    La presentación es clara, pero algunos puntos no están completamente desarrollados o la coherencia es limitada en ciertos aspectos.	
                    <br>
                    <strong>Regular (2-3 pts)</strong>
                    La presentación tiene varios puntos poco claros o mal estructurados. 
                    <br>
                    <strong>Deficiente (0-2 pts)</strong>
                    La presentación es confusa, sin estructura lógica y difícil de seguir.	
                    <br>
                    <strong>Peso</strong>
                    7%
                </label>
                <input placeholder="Digita un numero en el rango (1-5)" style="width: 50%; margin-top:10px;" type="number" name="claridad" id="claridad" class="form-control" min="1" max="5" required>
            </div>

            <div class="form-group">
                <label for="dominio" class="contenedor">
                <div class="title"><strong>Dominio del Tema (1-5)</strong></div>
                <br>
                <strong>Excelente (4-5 pts)</strong>
                Los estudiantes demuestran amplio conocimiento del tema, responden preguntas con confianza y manejan el contenido con soltura.	
                <br>
                <strong>Bueno (3-4 pts)</strong>
                Los estudiantes demuestran buen conocimiento del tema, pero algunas respuestas a preguntas son limitadas o poco claras.	
                <br>
                <strong>Regular (2-3 pts)</strong>
                Los estudiantes muestran conocimiento limitado y dudan en responder preguntas o explicar puntos clave.	
                <br>
                <strong>Deficiente (0-2 pts)</strong>
                Los estudiantes no muestran conocimiento sólido del tema y no logran responder preguntas adecuadamente.	
                <br>
                <strong>Peso</strong>
                5%
                </label>
                <input placeholder="Digita un numero en el rango (1-5)" style="width: 50%; margin-top:10px;" type="number" name="dominio" id="dominio" class="form-control" min="1" max="5" required>
            </div>

            <div class="form-group">
                <label class="contenedor" for="escalabilidad">
                <div class="title"><strong>Escalabilidad (1-5)</strong></div>
                    <br>
                    <strong>Excelente (4-5 pts)</strong>
                    La propuesta tiene un alto potencial de escalabilidad, con claras posibilidades de ampliación y desarrollo.	
                    <br>
                    <strong>Bueno (3-4 pts)</strong>
                    La propuesta tiene cierto potencial de escalabilidad, aunque necesita ajustes para mejorarla.	
                    <br>
                    <strong>Regular (2-3 pts)</strong>
                    La propuesta muestra limitaciones en su potencial de escalabilidad.	
                    <br>
                    <strong>Deficiente (0-2 pts)</strong>
                    La propuesta es difícil de escalar y tiene un bajo potencial de ampliación.	
                    <br>
                    <strong>Peso</strong>
                    4%
                </label>
                <input placeholder="Digita un numero en el rango (1-5)" style="width: 50%; margin-top:10px;" type="number" name="escalabilidad" id="escalabilidad" class="form-control" min="1" max="5" required>
            </div>

            <div class="form-group">
                <label for="beneficiarios" class="contenedor">
                <div class="title"><strong>Número de Beneficiarios (1-5)</strong></div>
                <br>
                <strong>Excelente (4-5 pts)</strong>
                La propuesta impacta a un número significativo de beneficiarios potenciales.	
                <br>
                <strong>Bueno (3-4 pts)</strong>
                La propuesta tiene un número aceptable de beneficiarios, pero podría optimizarse para más impacto.	
                <br>
                <strong>Regular (2-3 pts)</strong>
                El número de beneficiarios es limitado y podría mejorarse considerablemente.	
                <br>
                <strong>Deficiente (0-2 pts)</strong>
                La propuesta tiene un impacto mínimo en términos de beneficiarios potenciales.	
                <br>
                <strong>Peso</strong>
                4%
                </label>
                <input placeholder="Digita un numero en el rango (1-5)" style="width: 50%; margin-top:10px;" type="number" name="beneficiarios" id="beneficiarios" class="form-control" min="1" max="5" required>
            </div>

            <!-- Retroalimentación -->
            <div class="form-group">
                <label class="contenedor" for="retroalimentacion"><div class="title"><strong>Retroalimentación</strong></div></label>
                <textarea style="width: 50%; margin-top:10px;" placeholder="Agrega una retroalimentacion para el equipo..." name="retroalimentacion" id="retroalimentacion" class="form-control" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Guardar Calificación</button>
        </form>

        <br>
        <a href="equipos.php?categoria_id=<?php echo $equipo['categoria_id']; ?>" class="btn btn-secondary">Volver a Equipos</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
