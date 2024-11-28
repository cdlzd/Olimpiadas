-- Eliminar la base de datos si ya existe
DROP DATABASE IF EXISTS olimpiadas;

-- Crear la base de datos
CREATE DATABASE olimpiadas;

-- Seleccionar la base de datos
USE olimpiadas;

-- Crear la tabla de categorias
CREATE TABLE categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(255) NOT NULL
);

-- Crear la tabla de usuarios con el campo 'rol'
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasena VARCHAR(50) NOT NULL,
    rol ENUM('admin', 'usuario') NOT NULL DEFAULT 'usuario'
);

CREATE TABLE calificaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    categoria_id INT NOT NULL,  -- Este campo debe estar presente
    equipo_id INT NOT NULL,
    calificacion DECIMAL(3,2) NOT NULL,
    retroalimentacion TEXT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (categoria_id) REFERENCES categoria(id),
    FOREIGN KEY (equipo_id) REFERENCES equipos(id)
);


-- Insertar usuarios iniciales con contraseñas y roles
INSERT INTO usuarios (nombre_usuario, contrasena, rol) VALUES
('acastro', 'AmeC@stro2024', 'usuario'),
('dhernandez', 'DebH3rn@ndez', 'usuario'),
('evazquez', 'EliV@zquez24', 'usuario'),
('jarellano', 'Josu3@rellano', 'usuario'),
('eolivas', 'Els@Olivas', 'usuario'),
('mramirez', 'M@rcoR2024', 'usuario'),
('csodi', 'Car0linaS', 'usuario'),
('fdiez', 'Fr@nc0D!ez', 'usuario'),
('mmarin', 'Mel@nieM24', 'usuario'),
('xortiz', 'Xim3n@Ortiz', 'usuario'),
('mmarquez', 'Mari@naM2024', 'usuario'),
('ebautista', 'El!asB@utista', 'usuario'),
('gcastelan', 'Gr3t3lC@stelan', 'usuario'),
('jcobilt', 'Jos3C0bilt', 'usuario'),
('adaumas', 'Alv@roD2024', 'usuario'),
('astringer', 'Al!sonS24', 'usuario'),
('lcarrillo', 'Lu!sC@rrillo', 'usuario'),
('mperez', 'Mari@P3rez', 'usuario'),
('mflores', 'Maryb3thF24', 'usuario'),
('aluna', 'An@Luna2024', 'usuario'),
('lucia', 'lulu2024', 'usuario');

-- Inserta algunas categorías en la tabla `categoria`
INSERT INTO categoria (nombre_categoria) VALUES
('Axolotl'),
('Tochtli'),
('Papalotl'),
('Copitl');

-- Insertar equipos iniciales en la tabla equipos
-- (Asegúrate de que los IDs de la tabla categoria existan primero)
INSERT INTO equipos (nombre_equipo, categoria_id, imagen) VALUES
('Equipo Axolotl 1', 1, 'imagenes/axolotl1.png'),
('Equipo Axolotl 2', 1, 'imagenes/axolotl2.png'),
('Equipo Tochtli 1', 2, 'imagenes/tochtli1.png'),
('Equipo Papalotl 1', 3, 'imagenes/papalotl1.png'),
('Equipo Copitl 1', 4, 'imagenes/copitl1.png');

-- Crear índices para mejorar las consultas
CREATE INDEX idx_rol ON usuarios(rol);

