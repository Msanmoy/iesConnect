<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Asignatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $rol = $request->query('rol');

        $usuarios = Usuario::when($rol, fn ($query) => $query->where('rol', $rol))->paginate(10);

        return view('usuarios.index', compact('usuarios', 'rol'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:6|confirmed',
            'rol' => 'required|in:ESTUDIANTE,PROFESOR',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        Usuario::create($validated);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function show(Usuario $usuario)
    {
        $asignaturas = $usuario->rol === 'PROFESOR'
            ? $usuario->asignaturasImpartidas ?? []
            : $usuario->asignaturasInscritas ?? [];

        $tareas = $usuario->rol === 'ESTUDIANTE'
            ? $usuario->tareasAsignadas ?? []
            : $usuario->tareasCreadas ?? [];

        return view('usuarios.show', compact('usuario', 'asignaturas', 'tareas'));
    }

    public function edit(Usuario $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => "required|email|unique:usuarios,email,{$usuario->id}",
            'rol' => 'required|in:ESTUDIANTE,PROFESOR',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validated['password']) {
            $usuario->password = Hash::make($validated['password']);
        }

        $usuario->update(array_filter($validated));

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy(Usuario $usuario)
    {
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado.');
    }
}
