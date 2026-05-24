<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Auth\Auth;
use App\Models\Usuario;

class AuthController
{
    public function showRegistrationForm(Request $request): void
    {
        view('auth/register');
    }

    public function register(Request $request): void
    {
        $nombre = $request->input('nombre', '');
        $correo = $request->input('correo', '');
        $plain = $request->input('clave', '');

        $usuario = Usuario::where('correo', $correo)->first();

        if ($usuario) {
            back()
                ->with('error', 'El correo electrónico ya está registrado.')
                ->withInput([
                    'nombre' => $nombre,
                    'correo' => $correo
                ])->send();
        }

        $usuario = new Usuario();
        $usuario->nombre  = $nombre;
        $usuario->correo = $correo;
        $usuario->setClave($plain);
        $usuario->save();

        Auth::login($usuario);

        redirect(url('/productos'))->send();
    }

    public function showLoginForm(Request $request): void
    {
        view('auth/login');
    }

    public function login(Request $request): void
    {
        $credentials = [
            'correo' => $request->input('correo', ''),
            'clave' => $request->input('clave', '')
        ];

        if (Auth::attempt($credentials)) {
            session()->regenerate();
            redirect(url('/productos'))->send();
        }

        back()->with('error', 'Credenciales incorrectas')->withInput(['correo' => $credentials['correo']])->send();
    }

    public function logout(Request $request): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        redirect(url('/login'))->send();
    }
}
