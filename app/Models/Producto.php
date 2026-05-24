<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\QueryBuilder;

class Producto extends Model
{
    protected static string $table = 'productos';
    protected static array $columns = ['id', 'nombre', 'precio', 'stock', 'categoria_id'];

    // app/Models/Producto.php
    public function categoria(): Categoria
    {
        return Categoria::find($this->categoria_id);
    }

    public function ventaProductos(): QueryBuilder
    {
        return VentaProducto::where('producto_id', $this->{static::$primaryKey});
    }
}
