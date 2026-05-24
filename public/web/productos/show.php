<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../bootstrap/bootstrap.php';

use App\Core\Request;
use App\Http\Controllers\ProductoController;

try {
    $request = new Request();
    $id = (int) $request->input('id', 0);

    (new ProductoController())->show($id);
} catch (Throwable $e) {
    echo $e->getMessage() . "<br>" . $e->getTraceAsString();
}
