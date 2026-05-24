<?php

declare(strict_types=1);

namespace App\Core\Pagination;

require_once __DIR__ . '/../Request.php';

use App\Core\Request;

use IteratorAggregate;
use ArrayIterator;
use Countable;
use JsonSerializable;

class Paginator implements IteratorAggregate, Countable, JsonSerializable
{
    // Constructor Property Promotion (PHP 8)
    public function __construct(
        private array $items,
        private int $total,
        private int $perPage,
        private int $currentPage,
        private string $baseUrl,
        private array $query = []
    ) {}

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function jsonSerialize(): array
    {
        return [
            'data' => $this->items,
            'pagination' => [
                'total' => $this->total,
                'per_page' => $this->perPage,
                'current_page' => $this->currentPage,
                'last_page' => $this->lastPage(),
            ]
        ];
    }

    public function items(): array
    {
        return $this->items;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function perPage(): int
    {
        return $this->perPage;
    }

    public function hasPages(): bool
    {
        return $this->total > 0;
    }

    public function currentPage(): int
    {
        return $this->currentPage;
    }

    public function lastPage(): int
    {
        return (int) ceil($this->total / $this->perPage);
    }

    public function onFirstPage(): bool
    {
        return $this->currentPage === 1;
    }

    public function hasMorePages(): bool
    {
        return $this->lastPage() > $this->currentPage;
    }

    public function previousPageUrl(): ?string
    {
        return $this->currentPage() > 1
            ? $this->url($this->currentPage - 1)
            : null;
    }

    public function nextPageUrl(): ?string
    {
        return $this->hasMorePages()
            ? $this->url($this->currentPage + 1)
            : null;
    }

    public function url(int $page): string
    {
        $this->query['page'] = $page;
        return $this->baseUrl . '?' . http_build_query($this->query);
    }

    public function links(?string $view = null): string
    {
        // Ruta por defecto: la plantilla interna del core
        $defaultView = __DIR__ . '/pagination_links.php';

        // Si el usuario pasa un nombre de vista, buscamos en resources/views/
        $customView = $view !== null
            ? realpath(__DIR__ . "/../../../resources/views/{$view}.php")
            : false;

        // Usamos la personalizada si existe, o la interna si no
        $viewPath = $customView ?: $defaultView;

        $paginator = $this;
        ob_start();
        require $viewPath;
        return ob_get_clean();
    }
}
