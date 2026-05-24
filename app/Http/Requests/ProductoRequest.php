<?php

namespace App\Http\Requests;

use App\Core\FormRequest;
use App\Core\Validator;

class ProductoRequest extends FormRequest
{
    public function rules(Validator $v): void
    {
        $v->field('nombre')->required()->string()->minLength(3);
        $v->field('precio')->required()->numeric()->min(0);
        $v->field('stock')->required()->integer()->min(0);
        $v->field('categoria_id')->required()->integer()->min(1);
    }
}
