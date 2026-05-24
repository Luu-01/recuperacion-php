CREATE DATABASE IF NOT EXISTS 25_dwes_php_ud4_tienda;
USE 25_dwes_php_ud4_tienda;

CREATE TABLE venta_producto (
    id_venta_producto INT AUTO_INCREMENT PRIMARY KEY,
    venta_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    precio_venta DECIMAL(10,2) NOT NULL DEFAULT 0.00,

    FOREIGN KEY (venta_id) REFERENCES ventas(id_venta)
        ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id_producto)
        ON DELETE RESTRICT
);