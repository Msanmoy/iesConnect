<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPanelController extends Controller
{

    public function index()
    {
        return view('admin.index');
    }

    public function crearProfesor(Request $request)
    {

        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:6',
            'rol' => 'required|in:PROFESOR,ADMINISTRADOR',
        ]);



        \App\Models\Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'rol' => $request->rol,
        ]);

        return redirect()->route('admin.usuarios')->with('success', 'Usuario creado correctamente.');
    }

    public function asignaturas()
    {
        $asignaturas = \App\Models\Asignatura::with('profesor')->get();

        $profesores = \App\Models\Usuario::where('rol', 'PROFESOR')->get();

        return view('admin.asignaturas', compact('asignaturas', 'profesores'));
    }

    public function crearAsignatura(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'slug' => 'required|string|unique:asignaturas,slug',
            'codigo' => 'required|string|unique:asignaturas,codigo',
            'usuario_id' => 'required|exists:usuarios,id',
        ]);

        \App\Models\Asignatura::create([
            'nombre' => $request->nombre,
            'slug' => $request->slug,
            'descripcion' => $request->descripcion,
            'codigo' => $request->codigo,
            'usuario_id' => $request->usuario_id,
        ]);

        return back()->with('success', 'Asignatura creada correctamente.');
    }

    public function asignarProfesor(Request $request)
    {
        $request->validate([
            'asignatura_id' => 'required|exists:asignaturas,id',
            'usuario_id' => 'required|exists:usuarios,id',
        ]);

        $asignatura = \App\Models\Asignatura::findOrFail($request->asignatura_id);
        $asignatura->usuario_id = $request->usuario_id;
        $asignatura->save();

        return back()->with('success', 'Profesor asignado correctamente.');
    }

    public function usuarios()
    {
        $profesores = Usuario::where('rol', 'PROFESOR')->get();
        return view('admin.usuarios', compact('profesores'));
    }


    public function destroyAsignatura(Asignatura $asignatura)
    {
        $asignatura->delete();
        return back()->with('success', 'Asignatura eliminada correctamente.');
    }

    public function eliminarUsuario(Usuario $usuario)
    {
        if ($usuario->rol !== 'PROFESOR') {
            return back()->with('error', 'Solo puedes eliminar usuarios con rol PROFESOR.');
        }

        $usuario->delete();

        return back()->with('success', 'Profesor eliminado correctamente.');
    }

    public function dashboard()
    {
        $usuarios = \App\Models\Usuario::selectRaw('rol, COUNT(*) as total')
            ->groupBy('rol')
            ->pluck('total', 'rol');

        $tareas = \App\Models\Tarea::with('asignatura')
            ->get()
            ->groupBy('asignatura.nombre')
            ->map->count();

        return view('admin.index', [
            'usuarios' => $usuarios,
            'tareasPorAsignatura' => $tareas
        ]);
    }



}
