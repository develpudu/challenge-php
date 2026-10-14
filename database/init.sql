CREATE DATABASE IF NOT EXISTS productos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE productos;

CREATE TABLE IF NOT EXISTS productos (
    id INT(11) NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_precio (precio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_nombre ON productos (nombre);

INSERT INTO productos (nombre, descripcion, precio) VALUES
('Producto 1', 'Producto de prueba 1', 1020.00),
('Producto 2', 'Producto de prueba 2', 1574.53),
('Producto 3', 'Producto de prueba 3', 2096.75);
