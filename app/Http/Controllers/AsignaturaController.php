<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $asignatura = Asignatura::where('slug', $slug)->with(['recursos'])->firstOrFail();

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
            'usuario_id' => Auth::id(),
            'imagen' => $imagenPath ?? 'programacion.jpg'
        ]);

        $asignatura->usuarios()->attach(Auth::id());

        return redirect()->route('asignaturas.index')->with('success', 'Asignatura creada correctamente.');
    }

    public function unirse(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string',
        ]);

        $asignatura = Asignatura::where('codigo', $request->codigo)->first();

        if (!$asignatura) {
            return redirect()->back()->with('error', 'Código incorrecto. Inténtalo de nuevo.');
        }

        $usuario = auth()->user();

        if (!$usuario->asignaturas()->where('asignatura_id', $asignatura->id)->exists()) {
            $usuario->asignaturas()->attach($asignatura->id);
            $tareas = $asignatura->tareas;

            foreach ($tareas as $tarea) {
                $tarea->progresos()->create([
                    'usuario_id' => $usuario->id,
                    'nivel_asignado' => 'sencillo',
                ]);
            }

        }

        return redirect()->back()->with('success', 'Te has unido a la asignatura correctamente.');

    }

    public function regenerarCodigo(Asignatura $asignatura)
    {
        $this->autorizarAsignatura($asignatura);

        do {
            $nuevoCodigo = strtoupper(Str::random(7));
        } while (Asignatura::where('codigo', $nuevoCodigo)->exists());

        $asignatura->codigo = $nuevoCodigo;
        $asignatura->save();

        return redirect()->back()->with('success', 'El código de clase ha sido regenerado correctamente.');
    }

    private function autorizarAsignatura(Asignatura $asignatura)
    {
        $usuario = auth()->user();

        if ($usuario->rol !== 'PROFESOR' || $asignatura->usuario_id !== $usuario->id) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
    }

    public function personalizar(Request $request, Asignatura $asignatura)
    {
        $request->validate([
            'imagen' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            if ($asignatura->imagen) {
                Storage::disk('public')->delete($asignatura->imagen);
            }

            $ruta = $request->file('imagen')->store('asignaturas', 'public');
            $asignatura->update([
                'imagen' => $ruta
            ]);
        }

        return redirect()->back()->with('success', 'Imagen personalizada correctamente.');
    }

    public function trabajo($slug)
    {
        $asignatura = Asignatura::where('slug', $slug)->firstOrFail();
        return view('asignaturas.trabajo', compact('asignatura'));
    }

    public function personas($slug)
    {
        $asignatura = Asignatura::where('slug', $slug)->firstOrFail();
        $profesor = $asignatura->profesor;
        $alumnos = $asignatura->estudiantes;

        return view('asignaturas.personas', compact('asignatura', 'profesor', 'alumnos'));
    }

    public function expulsar(Asignatura $asignatura, Usuario $alumno)
    {
        if (auth()->user()->rol !== 'PROFESOR') {
            abort(403, 'No autorizado');
        }
        $asignatura->estudiantes()->detach($alumno->id);

        return redirect()->back()->with('success', 'Alumno expulsado correctamente.');
    }



}
