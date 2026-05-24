<?php

declare(strict_types=1);

namespace App\Core;

require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/Pagination/Paginator.php';

use App\Core\Pagination\Paginator;
use stdClass;

class QueryBuilder
{
    protected string $modelClass;

    protected string $select = '*';
    protected string $table;
    protected array $wheres = [];
    protected array $orders = [];
    protected ?int $limit = null;
    protected ?int $offset = null;

    protected array $params = [];

    public function __construct(string $table, string $modelClass)
    {
        $this->table = $table;
        $this->modelClass = $modelClass;
    }

    public function select(string ...$columns): static
    {
        $this->select = implode(', ', $columns);
        return $this;
    }

    public function selectRaw(string $raw): static
    {
        $this->select = $raw;
        return $this;
    }

    public function whereNull($column): static
    {
        $this->wheres[] = "$column IS NOT NULL";
        return $this;
    }

    public function whereNotNull($column): static
    {
        $this->wheres[] = "$column IS NOT NULL";
        return $this;
    }

    public function where(string $column, mixed $operator, mixed $value = null): static
    {
        if (func_num_args() == 2) {
            $value = $operator;
            $operator = '=';
        }
        if (is_null($value)) {
            if (in_array($operator, ['!=', '<>', 'IS NOT', 'is not'])) {
                $this->wheres[] = "$column IS NOT NULL";
            } else {
                $this->wheres[] = "$column IS NULL";
            }
            return $this;
        }

        // Sanitizar $column y resolver placeholder no repetido
        $base = preg_replace('/[^A-Za-z0-9_]+/', '_', $column);
        $placeholder =  $base;

        $i = 0;
        while (isset($this->params[$placeholder])) {
            $i++;
            $placeholder = $base . $i;
        }

        $this->wheres[] = "$column $operator :$placeholder";
        $this->params[$placeholder] = $value;

        return $this;
    }

    public function orderBy(string $column, string $order = 'ASC'): static
    {
        $this->orders[] = "$column $order";
        return $this;
    }

    public function limit(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    public function when($value, callable $callback): static
    {
        if (!(is_string($value) && trim($value) === '') &&
            !(is_array($value) && $value === [])) {
            $callback($this);
        }
        
        return $this;
    }

    public function get(): array
    {
        $sql = "SELECT {$this->select} FROM {$this->table}";

        if (!empty($this->wheres)) {
            $sql .= ' WHERE ' . implode(" AND ", $this->wheres);
        }

        if (!empty($this->orders)) {
            $sql .= ' ORDER BY ' . implode(", ", $this->orders);
        }

        if (!is_null($this->limit)) {
            $sql .= ' LIMIT ' . $this->limit;
        }

        if (!is_null($this->offset)) {
            $sql .= ' OFFSET ' . $this->offset;
        }

        return DB::select($sql, $this->params, $this->modelClass);
    }

    public function first(): ?object
    {
        $this->limit = 1;
        return $this->get()[0] ?? null;
    }

    public function count(): int
    {
        $clone = clone $this;

        $clone->orders = [];
        $clone->limit = null;
        $clone->offset = null;

        $clone->select = 'count(*) as total';
        $clone->modelClass = stdClass::class;

        return $clone->first()->total;
    }

    public function paginate(int $perPage = 15): Paginator
    {
        $request = new Request();
        $page = max(1, (int) $request->query('page', 1));

        // Clonar para posibilitar la reutilización posterior.
        $clone = clone $this;

        // Obtener el total de resultados
        $total = $clone->count();

        // Calcular y aplicar límites
        $offset = ($page - 1) * $perPage;
        $clone->limit($perPage)->offset($offset);

        // Ejecutar consulta
        $items = $clone->get();

        // Preparar URL y filtros
        $baseUrl = $request->path();
        $query = $request->query();
        unset($query['page']);

        return new Paginator($items, $total, $perPage, $page, $baseUrl, $query);
    }
}
