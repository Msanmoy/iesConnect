<?php

namespace App\Http\Controllers;

use App\Models\ArchivoTarea;
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
            'descripcion' => 'nullable|string',
            'fecha_limite' => 'nullable|date',
            'asignatura_id' => 'required|exists:asignaturas,id',
            'tipo' => 'required|in:tarea,cuestionario,pregunta,material,reutilizar',
            'archivos' => 'nullable|array',
            'archivos.*.*' => 'file|max:20480',
        ]);



        $tarea = Tarea::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha_limite' => $request->fecha_limite,
            'asignatura_id' => $request->asignatura_id,
            'tipo' => $request->tipo,
        ]);


        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $nivel => $archivosNivel) {
                foreach ($archivosNivel as $archivo) {
                    $ruta = $archivo->store('tareas', 'public');



                    $tarea->archivos()->create([
                        'nombre_archivo' => $archivo->getClientOriginalName(),
                        'ruta_archivo' => $ruta,
                        'tipo_archivo' => $archivo->getMimeType(),
                        'nivel' => $nivel,
                    ]);
                }
            }
        }


        $estudiantes = $tarea->asignatura->estudiantes;

        foreach ($estudiantes as $estudiante) {
            $tarea->progresos()->create([
                'usuario_id' => $estudiante->id,
                'nivel_asignado' => 'sencillo',
            ]);
        }

        foreach ($tarea->asignatura->estudiantes as $estudiante) {
            $estudiante->notify(new \App\Notifications\NewTaskNotification(
               'Se ha creado una nueva tarea: ' . $tarea->titulo,
               route('tareas.ver.estudiante', $tarea->id),
            ));
        }
        if ($tarea->tipo === 'cuestionario') {
            return redirect()->route('cuestionarios.edit', $tarea);
        }

        return redirect()->route('tareas.show', $tarea)->with('success', 'Tarea creada correctamente.');
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
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_limite' => 'nullable|date',
            'archivos' => 'nullable|array',
            'archivos.*.*' => 'file|max:2048',
        ]);

        $tarea->update([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha_limite' => $request->fecha_limite,
        ]);

        if ($request->has('archivos')) {
            foreach ($request->archivos as $nivel => $archivosNivel) {
                foreach ($archivosNivel as $archivo) {
                    if ($archivo) {
                        $path = $archivo->store('archivos_tarea', 'public');

                        $tarea->archivos()->create([
                            'nombre_archivo' => $archivo->getClientOriginalName(),
                            'ruta_archivo' => $path,
                            'tipo_archivo' => $archivo->getClientMimeType(),
                            'nivel' => $nivel,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('tareas.show', $tarea)->with('success', 'Tarea actualizada correctamente.');
    }


    public function destroy(Tarea $tarea)
    {
        $this->autorizarTarea($tarea);

        foreach ($tarea->archivos as $archivo) {
            Storage::disk('public')->delete($archivo->ruta_archivo);
        }

        $tarea->delete();

        return redirect()->route('asignaturas.show', $tarea->asignatura->slug)
            ->with('success', 'Tarea eliminada correctamente.');
    }

    private function autorizarTarea(Tarea $tarea)
    {
        if ($tarea->asignatura->usuario_id !== Auth::id()) {
            abort(403, 'No autorizado.');
        }
    }

}
