<?php

declare(strict_types=1);

namespace App\Core;

abstract class FormRequest extends Request
{
    protected array $validated = [];

    public abstract function rules(Validator $v): void;

    /**
     * Crea un FormRequest a partir del Request actual.
     *
     * El Router usa este método para validar sobre el Request ya procesado por
     * la aplicación, en lugar de volver a leer directamente las superglobales.
     */
    public static function fromRequest(Request $request): static
    {
        $formRequest = new static();
        $formRequest->data = $request->input();

        return $formRequest;
    }

    /** Ejecuta la validación y devuelve datos validados */
    public function validate(): array
    {
        $validator = new Validator($this);
        $this->rules($validator);
        return $this->validated = $validator->validate();
    }

    /** Devuelve los datos ya validados */
    public function validated(): array
    {
        return $this->validated;
    }
}
