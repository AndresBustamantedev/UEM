DROP DATABASE IF EXISTS inmobiliaria;
CREATE DATABASE inmobiliaria;
USE inmobiliaria;

CREATE TABLE usuario (
    usuario_id int(5) NOT NULL AUTO_INCREMENT,
    nombres varchar(35) NOT NULL,
    correo varchar(100) NOT NULL UNIQUE,
    clave varchar(255) NOT NULL,
    tipo_usuario varchar(20) NOT NULL, -- 'admin', 'comprador', 'vendedor'
    PRIMARY KEY (usuario_id)
);

CREATE TABLE pisos (
    Codigo_piso int NOT NULL AUTO_INCREMENT,
    calle VARCHAR(40) NOT NULL,
    numero INT NOT NULL,
    piso INT NOT NULL,
    puerta VARCHAR(5) NOT NULL,
    cp INT NOT NULL,
    metros INT NOT NULL,
    zona VARCHAR(15),
    precio float NOT NULL,
    imagen varchar(100) NOT NULL,
    usuario_id int(5),
    PRIMARY KEY (Codigo_piso),
    FOREIGN KEY (usuario_id) REFERENCES usuario(usuario_id) ON DELETE CASCADE
);

CREATE TABLE comprados (
    id_compra int NOT NULL AUTO_INCREMENT,
    usuario_comprador int(5),
    Codigo_piso int,
    Precio_final float NOT NULL,
    fecha_compra DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_compra),
    FOREIGN KEY (usuario_comprador) REFERENCES usuario(usuario_id),
    FOREIGN KEY (Codigo_piso) REFERENCES pisos(Codigo_piso)
);

-- Insertar usuario administrador por defecto (clave: admin123)
INSERT INTO usuario (nombres, correo, clave, tipo_usuario) VALUES 
('Administrador', 'admin@inmobiliaria.com', '$2y$10$e.w7w7w7w7w7w7w7w7w7w7w7w7w7w7w7w7w7w7w7w7w7w7w7w', 'admin');
-- Nota: El hash de arriba es un placeholder. Se recomienda usar reset_admin.php para establecer la contraseña correcta.

-- Insertar algunos usuarios de prueba (clave: 123456)
INSERT INTO usuario (nombres, correo, clave, tipo_usuario) VALUES 
('Vendedor 1', 'vendedor1@test.com', '$2y$10$G4e/lW/y.y/y.y/y.y/y.u/y.y/y.y/y.y/y.y/y.y/y.y/y.y', 'vendedor'),
('Comprador 1', 'comprador1@test.com', '$2y$10$G4e/lW/y.y/y.y/y.y/y.u/y.y/y.y/y.y/y.y/y.y/y.y/y.y', 'comprador');
