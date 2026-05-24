<?php

namespace App\Http\Requests;

use App\Core\FormRequest;
use App\Core\Validator;

class LoginRequest extends FormRequest
{
    public function rules(Validator $v): void
    {
        $v->field('correo')->required()->email();
        $v->field('clave')->required()->minLength(8);
    }
}
