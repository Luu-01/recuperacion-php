CREATE DATABASE IF NOT EXISTS 25_dwes_php_ud4_tienda;
USE 25_dwes_php_ud4_tienda;

CREATE TABLE ventas (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    total DECIMAL(10,2) NOT NULL DEFAULT 0.00
);