<?php

declare(strict_types=1);

namespace App\Core\Middleware;

use App\Core\Request;

interface Middleware
{
    public function handle(Request $request, ...$params): void;
}
