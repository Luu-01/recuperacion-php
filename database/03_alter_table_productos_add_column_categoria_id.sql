CREATE DATABASE IF NOT EXISTS 25_dwes_php_ud4_tienda;
USE 25_dwes_php_ud4_tienda;

-- Añadir relación categoría → producto
ALTER TABLE productos
ADD COLUMN categoria_id INT NOT NULL,
ADD FOREIGN KEY (categoria_id) REFERENCES categorias(id_categoria)
    ON DELETE RESTRICT;