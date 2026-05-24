-- ==============================================
-- Script completo: 00_full_export.sql
-- Crea y carga toda la base de datos
-- Proyecto: 25_dwes_php_ud4_tienda
-- ==============================================

-- 1️⃣ Crear base de datos
DROP DATABASE IF EXISTS 25_dwes_php_ud4_tienda;

CREATE DATABASE 25_dwes_php_ud4_tienda
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE 25_dwes_php_ud4_tienda;

-- 2️⃣ Tablas principales
-- Productos
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0
);

-- Categorías
CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(50) NOT NULL
);

-- Añadir relación categoría → producto
ALTER TABLE productos
ADD COLUMN categoria_id INT NOT NULL,
ADD FOREIGN KEY (categoria_id) REFERENCES categorias(id_categoria)
    ON DELETE RESTRICT;

-- Ventas
CREATE TABLE ventas (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    total DECIMAL(10,2) NOT NULL DEFAULT 0.00
);

-- VentaProducto (tabla intermedia)
CREATE TABLE venta_producto (
    id_venta_producto INT AUTO_INCREMENT PRIMARY KEY,
    venta_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    precio_venta DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    FOREIGN KEY (venta_id) REFERENCES ventas(id_venta)
        ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id)
        ON DELETE RESTRICT
);

-- Usuarios
CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    clave VARCHAR(255) NOT NULL
);

-- 3️⃣ Datos de prueba
-- Categorías
INSERT INTO categorias (nombre_categoria) VALUES
('Periféricos'),
('Componentes'),
('Monitores');

-- Productos
INSERT INTO productos (nombre, precio, stock, categoria_id) VALUES
('Ratón inalámbrico óptico', 14.99, 25, 1),
('Teclado mecánico retroiluminado', 49.90, 12, 1),
('Alfombrilla para ratón gaming plus', 11.50, 40, 1),
('Auriculares con micrófono', 59.95, 10, 1),
('Cámara web HD', 34.99, 8, 1),
('Micrófono USB de escritorio', 69.00, 6, 1),
('Concentrador USB 3.0 de 4 puertos', 13.95, 30, 1),
('Procesador de seis núcleos', 199.00, 5, 2),
('Placa base micro ATX', 129.00, 7, 2),
('Memoria RAM DDR4 16 GB', 74.99, 15, 2),
('Unidad SSD NVMe 1 TB', 89.50, 10, 2),
('Fuente de alimentación 650 W', 64.95, 9, 2),
('Tarjeta gráfica gama media', 449.00, 3, 2),
('Ventilador para CPU', 29.90, 20, 2),
('Monitor 24 pulgadas 144 Hz', 189.00, 8, 3),
('Monitor 27 pulgadas curvo', 269.00, 4, 3),
('Monitor 24 pulgadas Full HD', 109.00, 10, 3),
('Monitor 27 pulgadas QHD', 309.00, 6, 3),
('Monitor 24 pulgadas IPS', 169.00, 9, 3),
('Monitor 25 pulgadas con USB-C', 279.00, 5, 3),
('Televisor', 450.00, 10, 1);

-- Ventas
INSERT INTO ventas (total) VALUES
(79.88),
(681.99);

-- Líneas de venta
INSERT INTO venta_producto (venta_id, producto_id, cantidad, precio_venta) VALUES
-- Venta 1
(1, 1, 2, 14.99),   -- Ratón inalámbrico óptico
(1, 2, 1, 49.90),   -- Teclado mecánico retroiluminado

-- Venta 2
(2, 1, 1, 14.99),    -- Ratón inalámbrico óptico
(2, 13, 1, 449.00),  -- Tarjeta gráfica gama media
(2, 17, 2, 109.00);  -- Monitor 24 pulgadas Full HD
