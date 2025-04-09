<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\Tarea;
use App\Models\Tema;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    public function create()
    {
        $usuario = auth()->user();

        if (!$usuario || $usuario->rol !== 'PROFESOR') {
            abort(403, 'No tienes permiso para realizar esta acción');
        }

        // Obtener los temas que pertenecen a aulas donde el profesor es el propietario
        $temas = Tema::whereHas('aula', function ($query) use ($usuario) {
            $query->where('profesor_id', $usuario->id);
        })->with('aula')->get();

        return view('profesor.tareas.create', compact('temas'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'tema_id' => 'required|exists:temas,id',
            'fases' => 'required|array',
            'fases.*.nivel' => 'required|string|in:basico,intermedio,avanzado',
            'fases.*.preguntas' => 'required|array',
            'fases.*.preguntas.*.enunciado' => 'required|string',
            'fases.*.preguntas.*.respuestas' => 'required|array|min:1',
            'fases.*.preguntas.*.respuestas.*.respuesta' => 'required|string',
            'fases.*.preguntas.*.respuestas.*.correcta' => 'boolean',
        ]);

        $profesor = auth()->user();

        if ($profesor -> rol !== 'PROFESOR') {
            abort(403, 'No tienes permiso para realizar esta acción');
        }

        $tarea = $profesor->tareas()->create([
            'nombre' => $data['nombre'],
            'tema_id' => $data['tema_id'],
            'eliminado' => false,
            'visible' => true,
        ]);

        foreach ($data['fases'] as $faseData) {
            $fase = $tarea->fases()->create([
                'nivel' => $faseData['nivel'],
                'nombre_archivo' => $faseData['nombre_archivo'] ?? null,
            ]);

            foreach ($faseData['preguntas'] as $preguntaData) {
                $pregunta = $fase->preguntas()->create([
                    'enunciado' => $preguntaData['enunciado'],
                    'nombre_archivo' => $preguntaData['nombre_archivo'] ?? null,
                ]);

                foreach ($preguntaData['respuestas'] as $respuestaData) {
                    $pregunta->respuestas()->create([
                        'respuesta' => $respuestaData['respuesta'],
                        'correcta' => $respuestaData['correcta'] ?? false,
                    ]);
                }
            }
        }

        return redirect()->route('tareas.index')->with('success', 'Tarea creada correctamente');
    }

}
