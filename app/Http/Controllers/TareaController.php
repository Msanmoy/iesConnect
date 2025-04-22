<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TareaController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        if (!$usuario->esEstudiante()) {
            abort(403);
        }

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

        return view('tareas.index', compact('tareas'));
    }

    public function create()
    {
        $asignaturas = Asignatura::where('usuario_id', Auth::id())->get();
        return view('tareas.create', compact('asignaturas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_limite' => 'nullable|date',
            'asignatura_id' => 'required|exists:asignaturas,id',
            'archivos.*' => 'nullable|file|max:20480',
        ]);

        $tarea = Tarea::create($request->only(['titulo', 'descripcion', 'fecha_limite', 'asignatura_id']));

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

        return redirect()->route('tareas.index')->with('success', 'Tarea creada correctamente.');
    }

    public function show(Tarea $tarea)
    {
        $this->autorizarTarea($tarea);
        return view('tareas.show', compact('tarea'));
    }

    public function edit(Tarea $tarea)
    {
        $this->autorizarTarea($tarea);

        $asignaturas = Asignatura::where('usuario_id', Auth::id())->get();
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
