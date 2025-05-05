<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TareaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $usuario = Auth::user();

        if ($usuario->rol == 'PROFESOR') {
            $tareas = Tarea::with('asignatura')
                ->whereHas('asignatura', fn($q) => $q->where('usuario_id', $usuario->id))
                ->get();
        } else if ($usuario->rol == 'ESTUDIANTE'){
            $tareas = Tarea::with([
                'asignatura',
                'progresos' => function ($q) use ($usuario) {
                    $q->where('usuario_id', $usuario->id)->with('entregas');
                }
            ])
                ->whereHas('progresos', function ($q) use ($usuario) {
                    $q->where('usuario_id', $usuario->id);
                })
                ->get();
        } else {
            abort(403, 'No autorizado.');
        }

        return view('tareas.index', compact('tareas'));
    }

    public function create(Request $request)
    {
        $asignaturaId = $request->input('asignatura_id');
        $asignatura = Asignatura::findOrFail($asignaturaId);

        return view('tareas.create', compact('asignatura'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha_limite' => 'required|date|after:today',
            'asignatura_id' => 'required|exists:asignaturas,id',
        ]);

        $tarea = Tarea::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha_limite' => $request->fecha_limite,
            'asignatura_id' => $request->asignatura_id,
            'profesor_id' => auth()->id(),
        ]);

        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $path = $archivo->store('tareas', 'public');

                $tarea->archivos()->create([
                    'nombre_archivo' => $archivo->getClientOriginalName(),
                    'ruta_archivo' => $path,
                ]);
            }
        }

        return redirect()->route('asignaturas.show', $tarea->asignatura->slug)
            ->with('success', 'Tarea creada correctamente.');
    }


    public function show(Tarea $tarea)
    {
        $this->autorizarTarea($tarea);

        $tarea->load(['asignatura', 'progresos.estudiante', 'progresos.entregas']);

        return view('tareas.show', compact('tarea'));
    }

    public function showEstudiante(Tarea $tarea)
    {
        $usuario = auth()->user();

        $progreso = $tarea->progresos()
            ->where('usuario_id', $usuario->id)
            ->with('entregas')
            ->firstOrFail();

        $tarea->load('archivos');

        return view('tareas.show-estudiante', compact('tarea', 'progreso'));
    }

    public function edit(Tarea $tarea)
    {
        $this->autorizarTarea($tarea);

        $asignaturas = Asignatura::where('usuario_id', Auth::id())->get();
        $tarea->load('archivos');

        return view('tareas.edit', compact('tarea', 'asignaturas'));
    }

    public function update(Request $request, Tarea $tarea)
    {
        $this->autorizarTarea($tarea);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_limite' => 'nullable|date',
            'asignatura_id' => 'required|exists:asignaturas,id',
            'archivos.*' => 'nullable|file|max:20480',
        ]);

        $tarea->update($request->only(['titulo', 'descripcion', 'fecha_limite', 'asignatura_id']));

        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $ruta = $archivo->store('archivos_tareas', 'public');
                $tarea->archivos()->create([
                    'nombre_archivo' => $archivo->getClientOriginalName(),
                    'ruta_archivo' => $ruta,
                    'tipo_archivo' => $archivo->getMimeType(),
                ]);
            }
        }

        return redirect()->route('tareas.index')->with('success', 'Tarea actualizada correctamente.');
    }

    public function destroy(Tarea $tarea)
    {
        $this->autorizarTarea($tarea);

        foreach ($tarea->archivos as $archivo) {
            Storage::disk('public')->delete($archivo->ruta_archivo);
        }

        $tarea->delete();

        return redirect()->route('tareas.index')->with('success', 'Tarea eliminada.');
    }

    private function autorizarTarea(Tarea $tarea)
    {
        if ($tarea->asignatura->usuario_id !== Auth::id()) {
            abort(403, 'No autorizado.');
        }
    }

}
