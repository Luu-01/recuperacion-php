CREATE DATABASE IF NOT EXISTS 25_dwes_php_ud4_tienda;
USE 25_dwes_php_ud4_tienda;

- ==============================================
-- Ventas (totales coherentes)
-- ==============================================

-- Venta 1:
--  2 × Ratón inalámbrico óptico  →  2 × 14.99 = 29.98
--  1 × Teclado mecánico          →  1 × 49.90 = 49.90
--  Total: 79.88

-- Venta 2:
--  1 × Ratón inalámbrico óptico  →  1 × 14.99  =  14.99
--  1 × Tarjeta gráfica gama media → 1 × 449.00 = 449.00
--  2 × Monitor 24" Full HD        → 2 × 109.00 = 218.00
--  Total: 667.00

INSERT INTO ventas (total) VALUES
(79.88),
(681.99);

-- ==============================================
-- Líneas de venta (tabla venta_producto)
-- ==============================================

INSERT INTO venta_producto (venta_id, producto_id, cantidad, precio_venta) VALUES
-- Venta 1
(1, 1, 2, 14.99),   -- Ratón inalámbrico óptico
(1, 2, 1, 49.90),   -- Teclado mecánico retroiluminado

-- Venta 2
(2, 1, 1, 14.99),    -- Ratón inalámbrico óptico
(2, 13, 1, 449.00),  -- Tarjeta gráfica gama media
(2, 17, 2, 109.00);  -- Monitor 24 pulgadas Full HD