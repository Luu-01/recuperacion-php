<?php

declare(strict_types=1);

namespace App\Core;

require_once __DIR__ . '/../Core/DB.php';
require_once __DIR__ . '/../Core/QueryBuilder.php';

use App\Core\DB;

abstract class Model implements \JsonSerializable
{
    protected static string $table;
    protected static string $primaryKey = 'id';
    protected static array $columns = [];

    protected static array $visible = [];
    protected static array $hidden = [];

    protected array $attributes = [];

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function getKey(): mixed
    {
        return $this->{static::$primaryKey};
    }

    public function toDatabaseArray(): array
    {
        return array_intersect_key($this->attributes, array_flip(static::$columns));
    }

    public function toArray(): array
    {
        $columns = static::$columns;

        // Si $visible no está vacío, se prioriza
        if (!empty(static::$visible)) {
            $columns = array_intersect($columns, static::$visible);
        }

        // Si hay campos ocultos, se excluyen
        if (!empty(static::$hidden)) {
            $columns = array_diff($columns, static::$hidden);
        }

        // Construcción del array final
        $data = [];
        foreach ($columns as $col) {
            if (array_key_exists($col, $this->attributes)) {
                $data[$col] = $this->attributes[$col];
            }
        }

        return $data;
    }

    public function __set(string $name, mixed $value): void
    {
        $this->attributes[$name] = $value;
    }

    public function __get(string $name): mixed
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        if (method_exists($this, $name)) {
            $result = $this->$name();
            if ($result instanceof QueryBuilder) {
                return $result->get();
            }
            return $result;
        }

        return null;
    }

    public function __isset(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }

    public static function __callStatic($name, $arguments)
    {
        return static::query()->$name(...$arguments);
    }

    public static function query(): QueryBuilder
    {
        return new QueryBuilder(static::$table, static::class);
    }

    public static function hydrate(array $data): static
    {
        $obj = new static();
        $obj->fill($data);

        return $obj;
    }

    public static function create(array $data): static
    {
        $model = static::hydrate($data);
        $model->save();
        return $model;
    }

    public function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public static function all(): array
    {
        $sql = "SELECT * FROM " . static::$table;
        return DB::select($sql, [], static::class);
    }

    public static function find(int $id): ?self
    {
        $sql = "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = :" . static::$primaryKey;
        $params = [static::$primaryKey => $id];
        $result = DB::select($sql, $params, static::class);
        return !empty($result) ? $result[0] : null;
    }

    public function save(): int
    {
        if ($this->{static::$primaryKey}) {
            $this->update();
            /*
            App\Core\Model::save(): Return value must be of type int, string returned
            #0 C:\xampp\htdocs\2526_servidor\php\2526_practicas\p5_7\public\productos\update.php(22): App\Core\Model->save() #1 {main}
            */
            return (int)$this->{static::$primaryKey}; // Casting añadido
        }
        return $this->insert();
    }

    public function insert(): int
    {
        // Todas las columnas excepto la PK
        $fields = array_diff(static::$columns, [static::$primaryKey]);

        /*
        SQLSTATE[HY093]: Invalid parameter number: number of bound variables does not match number of tokens
        */

        // Columnas que realmente tienen valor en el modelo
        $fields = array_keys(
            array_intersect_key(
                $this->attributes,
                array_flip($fields)
            )
        );

        $fieldList = implode(', ', $fields);
        $placeholders = implode(', ', array_map(fn($f) => ":$f", $fields));

        $sql = "INSERT INTO " . static::$table . " ($fieldList) VALUES ($placeholders)";
        
        $params = array_intersect_key($this->attributes, array_flip($fields));

        return $this->{static::$primaryKey} = DB::insert($sql, $params);
    }

    public function update(): bool
    {
        $fields = array_diff(static::$columns, [static::$primaryKey]);
        $set = implode(', ', array_map(fn($f) => "$f = :$f", $fields));

        $sql = "UPDATE " . static::$table . " SET $set WHERE " . static::$primaryKey . " = :" . static::$primaryKey;
        $params = $this->toArray();

        return DB::update($sql, $params) === 1;
    }

    public function delete(): bool
    {
        $sql = "DELETE FROM " . static::$table . " WHERE " . static::$primaryKey . " = :" . static::$primaryKey;
        $params = [static::$primaryKey => $this->{static::$primaryKey}];

        return DB::delete($sql, $params) === 1;
    }
}
