CREATE DATABASE IF NOT EXISTS 25_dwes_php_ud4_tienda;
USE 25_dwes_php_ud4_tienda;

CREATE TABLE IF NOT EXISTS categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(50) NOT NULL
);