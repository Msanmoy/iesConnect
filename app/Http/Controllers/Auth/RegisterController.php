<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegistrationForm() {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre'     => ['required', 'string', 'min:1',  'max:20'],
            'apellidos'  => ['required', 'string', 'min:3',  'max:50'],
            'email'      => [
                'required',
                'string',
                'email',
                'regex:/^[A-Za-z0-9._%+-]{3,}@g\.educaand\.es$/',
                'max:255',
                'unique:usuarios,email',
            ],
            'password'   => [
                'required',
                'confirmed',
                Password::min(8)
                ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'email.regex'   => 'El correo debe terminar en @g.educaand.es y tener al menos 3 caracteres antes de la @.',
            'password.*'    => 'La contraseña debe tener 8+ caráct., mayúscula, minúscula, número y símbolo.',
        ]);

        $user = Usuario::create([
            'nombre'     => $request->nombre,
            'apellidos'  => $request->apellidos,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'rol'        => 'ESTUDIANTE',
        ]);

        Auth::login($user);

        return redirect()
            ->route('asignaturas.index')
            ->with('success', 'Usuario creado correctamente');
    }
}
