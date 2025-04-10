<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AsignaturaController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        $asignaturas = $usuario->rol === 'PROFESOR'
            ? $usuario->asignaturasImpartidas()->get()
            : $usuario->asignaturas()->get();

        return view('asignaturas.index', compact('asignaturas'));
    }

    public function show($slug)
    {
        $asignatura = Asignatura::where('slug', $slug)->with(['tareas.fases', 'recursos'])->firstOrFail();

        return view('asignaturas.show', compact('asignatura'));
    }

    public function create()
    {
        return view('asignaturas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|max:2048'
        ]);

        $imagenPath = null;

        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('asignaturas', 'public');
        }

        $asignatura = Asignatura::create([
            'nombre' => $request->nombre,
            'slug' => Str::slug($request->nombre) . '-' . uniqid(),
            'descripcion' => $request->descripcion,
            'codigo_unico' => strtoupper(Str::random(6)),
            'profesor_id' => Auth::id(),
            'imagen' => $imagenPath ?? 'default.png'
        ]);

        // Asociar al profesor automáticamente
        $asignatura->usuarios()->attach(Auth::id());

        return redirect()->route('asignaturas.asignaturas')->with('success', 'Asignatura creada correctamente.');
    }

    public function unirse(Request $request)
    {
        $request->validate([
            'codigo_clase' => 'required|string|exists:asignaturas,codigo_unico',
        ]);

        $asignatura = Asignatura::where('codigo_unico', $request->codigo_clase)->first();

        // Evitar múltiples uniones
        if ($asignatura->usuarios()->where('usuario_id', Auth::id())->exists()) {
            return back()->withErrors(['codigo_clase' => 'Ya estás unido a esta clase.']);
        }

        $asignatura->usuarios()->attach(Auth::id());

        return redirect()->route('asignaturas.asignaturas')->with('success', 'Te has unido a la clase.');
    }
}
