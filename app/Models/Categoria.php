<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\QueryBuilder;

class Categoria extends Model
{
    protected static string $table = 'categorias';
    protected static string $primaryKey = 'id_categoria';
    protected static array $columns = ['id_categoria', 'nombre_categoria'];

    // app/Models/Categoria.php
    public function productos(): QueryBuilder
    {
        // return Producto::where('categoria_id', $this->id_categoria);
        return Producto::where('categoria_id', $this->{static::$primaryKey});
    }
}
