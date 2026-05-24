<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Request;
use App\Http\Requests\ProductoRequest;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoController
{
    public function index(Request $request): void
    {
        $nombre = $request->query('nombre', '');
        $categoria_id = $request->query('categoria_id', '');

        $productos = Producto::query()
            ->when($nombre, fn($q) => $q->where('nombre', 'LIKE', "%$nombre%"))
            ->when($categoria_id, fn($q) => $q->where('categoria_id', $categoria_id))
            ->orderBy('precio')
            ->paginate(5);

        $categorias = Categoria::all();

        view('productos/index', compact(
            'productos',
            'categorias',
            'nombre',
            'categoria_id'
        ));
    }

    public function show(Request $request): void
    {
        $id = (int) $request->input('id');

        $producto = Producto::find($id)
            ?? throw new \Exception('Producto no encontrado');

        view('productos/show', compact('producto'));
    }

    public function create(Request $request): void
    {
        $categorias = Categoria::all();
        view('productos/create', compact('categorias'));
    }

    public function store(Request $request): void
    {
        // La ruta entrega siempre App\Core\Request. Para conservar las reglas
        // existentes, se crea ProductoRequest solo en el punto de validación.
        $validated = (new ProductoRequest())->validate();

        Producto::create($validated);
        redirect(url('/productos'))->with('success', 'Producto guardado con éxito')->send();
    }

    public function edit(Request $request): void
    {
        $id = (int) $request->input('id');

        $producto = Producto::find($id)
            ?? throw new \Exception('Producto no encontrado');

        $categorias = Categoria::all();

        view('productos/edit', compact('producto', 'categorias'));
    }

    public function update(Request $request): void
    {
        $id = (int) $request->input('id');

        $producto = Producto::find($id)
            ?? throw new \Exception('Producto no encontrado');

        $validated = (new ProductoRequest())->validate();

        $producto->fill($validated);
        $producto->save();

        redirect(url('/productos'))->with('success', 'Producto actualizado con éxito')->send();
    }

    public function destroy(Request $request): void
    {
        $id = (int) $request->input('id');

        $producto = Producto::find($id)
            ?? throw new \Exception('Producto no encontrado');

        $producto->delete();

        redirect(url('/productos'))->with('success', 'Producto eliminado con éxito')->send();
    }
}
