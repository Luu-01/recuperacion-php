<?php

namespace App\Http\Requests;

use App\Core\FormRequest;
use App\Core\Validator;

class RegisterRequest extends FormRequest
{
    public function rules(Validator $v): void
    {
        $v->field('nombre')->required()->string()->minLength(3);
        $v->field('correo')->required()->email();
        $v->field('clave')->required()->minLength(8);
    }
}
