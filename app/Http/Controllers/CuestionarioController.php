<?php

namespace App\Http\Controllers;

use App\Models\Pregunta;
use App\Models\RespuestaEstudiante;
use App\Models\Tarea;
use Illuminate\Http\Request;

class CuestionarioController extends Controller
{
    public function edit(Tarea $tarea)
    {
        $tarea->load('preguntas.respuestas');
        return view('cuestionarios.edit', compact('tarea'));
    }

    public function storePregunta(Request $request, Tarea $tarea)
    {

        $data = $request->validate([
            'enunciado' => 'required|string',
            'respuestas' => 'required|array|min:2',
            'respuestas.*.texto' => 'required|string',
            'respuestas.*.es_correcta' => 'boolean',
        ]);

        $pregunta = $tarea->preguntas()->create([
            'enunciado' => $data['enunciado'],
        ]);

        $correctaIndex = $request->input('respuestas_correcta');

        foreach ($data['respuestas'] as $index => $respuesta) {
            $pregunta->respuestas()->create([
                'texto' => $respuesta['texto'],
                'es_correcta' => ((string)$index === (string)$correctaIndex),
            ]);
        }

        return back()->with('success', 'Pregunta añadida correctamente.');
    }

    public function formularioEstudiante(Tarea $tarea)
    {
        $usuario = auth()->user();

        $yaRespondio = RespuestaEstudiante::where('usuario_id', $usuario->id)
            ->whereIn('pregunta_id', $tarea->preguntas->pluck('id'))
            ->exists();

        if ($yaRespondio) {
            return redirect()->route('cuestionarios.resultado', $tarea);
        }

        $tarea->load('preguntas.respuestas');
        return view('cuestionarios.responder', compact('tarea'));
    }


    public function guardarRespuestas(Request $request, Tarea $tarea)
    {
        $usuario = auth()->user();

        foreach ($request->input('respuestas', []) as $preguntaId => $respuestaId) {
            RespuestaEstudiante::updateOrCreate(
                [
                    'usuario_id' => $usuario->id,
                    'pregunta_id' => $preguntaId,
                ],
                [
                    'respuesta_id' => $respuestaId,
                ]
            );
        }

        return redirect()->route('tareas.ver.estudiante', $tarea)->with('success', 'Respuestas enviadas correctamente.');
    }

    public function estadisticas(Tarea $tarea)
    {
        $this->autorizarTarea($tarea); // solo profesores

        $tarea->load(['preguntas.respuestas']);

        $totalEstudiantes = $tarea->asignatura->estudiantes()->count();

        $respondieron = RespuestaEstudiante::whereIn('pregunta_id', $tarea->preguntas->pluck('id'))
            ->distinct('usuario_id')
            ->count('usuario_id');

        // Cálculo por pregunta
        $estadisticasPreguntas = $tarea->preguntas->map(function ($pregunta) {
            $totalRespuestas = $pregunta->respuestasEstudiante()->count();
            $respuestasCorrectas = RespuestaEstudiante::where('pregunta_id', $pregunta->id)
                ->whereHas('respuesta', fn($q) => $q->where('es_correcta', true))
                ->count();

            return [
                'pregunta' => $pregunta->enunciado,
                'total' => $totalRespuestas,
                'correctas' => $respuestasCorrectas,
                'porcentaje' => $totalRespuestas > 0 ? round(($respuestasCorrectas / $totalRespuestas) * 100) : 0,
            ];
        });

        return view('cuestionarios.estadisticas', compact('tarea', 'totalEstudiantes', 'respondieron', 'estadisticasPreguntas'));
    }

    public function updatePregunta(Request $request, Pregunta $pregunta)
    {
        $data = $request->validate([
            'enunciado' => 'required|string',
            'respuestas' => 'required|array|min:2',
            'respuestas.*.id' => 'nullable|exists:respuestas,id',
            'respuestas.*.texto' => 'required|string',
        ]);

        $correctaIndex = $request->input("correcta_{$pregunta->id}");

        $pregunta->update(['enunciado' => $data['enunciado']]);

        foreach ($data['respuestas'] as $index => $respuestaData) {
            $respuestaData['es_correcta'] = ((string)$correctaIndex === (string)$index);

            if (!empty($respuestaData['id'])) {
                $respuesta = Respuesta::find($respuestaData['id']);
                if ($respuesta && $respuesta->pregunta_id === $pregunta->id) {
                    $respuesta->update([
                        'texto' => $respuestaData['texto'],
                        'es_correcta' => $respuestaData['es_correcta'],
                    ]);
                }
            } else {
                $pregunta->respuestas()->create([
                    'texto' => $respuestaData['texto'],
                    'es_correcta' => $respuestaData['es_correcta'],
                ]);
            }
        }

        return back()->with('success', 'Pregunta actualizada correctamente.');
    }

    public function destroyPregunta(Pregunta $pregunta)
    {
        $this->autorizarTarea($pregunta->tarea);
        $pregunta->delete();

        return back()->with('success', 'Pregunta eliminada correctamente.');
    }

    private function autorizarTarea(Tarea $tarea)
    {
        if ($tarea->asignatura->usuario_id !== auth()->id()) {
            abort(403, 'No autorizado.');
        }
    }

    public function verResultado(Tarea $tarea)
    {
        $usuario = auth()->user();

        $tarea->load(['preguntas.respuestas']);

        $respuestasEstudiante = \App\Models\RespuestaEstudiante::where('usuario_id', $usuario->id)
            ->whereIn('pregunta_id', $tarea->preguntas->pluck('id'))
            ->with('respuesta')
            ->get()
            ->keyBy('pregunta_id');

        $total = $tarea->preguntas->count();
        $correctas = 0;

        foreach ($tarea->preguntas as $pregunta) {
            $respuestaEstudiante = $respuestasEstudiante[$pregunta->id] ?? null;
            if ($respuestaEstudiante && $respuestaEstudiante->respuesta->es_correcta) {
                $correctas++;
            }
        }

        $porcentaje = $total > 0 ? round(($correctas / $total) * 100) : 0;

        return view('cuestionarios.resultado', compact('tarea', 'respuestasEstudiante', 'correctas', 'total', 'porcentaje'));
    }

}
