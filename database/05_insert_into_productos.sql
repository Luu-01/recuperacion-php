CREATE DATABASE IF NOT EXISTS 25_dwes_php_ud4_tienda;
USE 25_dwes_php_ud4_tienda;

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
('Monitor 25 pulgadas con USB-C', 279.00, 5, 3);
