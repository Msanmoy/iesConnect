<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Respuesta;
use App\Models\RespuestaEstudiante;
use Illuminate\Http\Request;

class CuestionarioRespuestaController extends Controller
{
    public function formulario(Tarea $tarea)
    {
        $usuario = auth()->user();
        $cuestionario = $tarea->cuestionario()->with('preguntas.respuestas')->firstOrFail();

        $yaRespondio = RespuestaEstudiante::where('usuario_id', $usuario->id)
            ->whereIn('pregunta_id', $cuestionario->preguntas->pluck('id'))
            ->exists();

        if ($yaRespondio) {
            return redirect()->route('cuestionarios.resultado', $tarea);
        }

        $nivel = null;
        if (!$tarea->es_generica) {
            $nivel = $tarea->progresos()->where('usuario_id', $usuario->id)->value('nivel_asignado');
        }

        $preguntas = $cuestionario->preguntas->filter(fn($p) => $tarea->es_generica || $p->nivel === $nivel);

        return view('cuestionarios.responder', compact('tarea', 'preguntas'));
    }

    public function guardar(Request $request, Tarea $tarea)
    {
        $usuario = auth()->user();
        $cuestionario = $tarea->cuestionario()->with('preguntas.respuestas')->firstOrFail();

        $total = 0;

        foreach ($cuestionario->preguntas as $pregunta) {
            if ($pregunta->tipo === 'test') {
                $respuestaId = $request->input("respuestas.{$pregunta->id}");

                if ($respuestaId) {
                    $respuesta = Respuesta::find($respuestaId);
                    $esCorrecta = $respuesta && $respuesta->es_correcta;
                    $nota = $esCorrecta ? $pregunta->puntos : 0;

                    RespuestaEstudiante::create([
                        'usuario_id' => $usuario->id,
                        'pregunta_id' => $pregunta->id,
                        'respuesta_id' => $respuesta->id,
                        'nota' => $nota,
                    ]);

                    $total += $nota;
                }
            } else {
                $texto = $request->input("respuestas_abiertas.{$pregunta->id}");

                if ($texto) {
                    RespuestaEstudiante::create([
                        'usuario_id' => $usuario->id,
                        'pregunta_id' => $pregunta->id,
                        'respuesta_texto' => $texto,
                        'nota' => null,
                    ]);
                }
            }
        }

        return redirect()->route('cuestionarios.resultado', $tarea)->with('success', 'Cuestionario enviado correctamente.');
    }

    public function verResultado(Tarea $tarea)
    {
        $usuario = auth()->user();
        $cuestionario = $tarea->cuestionario()->with('preguntas.respuestas')->firstOrFail();

        $respuestas = RespuestaEstudiante::where('usuario_id', $usuario->id)
            ->whereIn('pregunta_id', $cuestionario->preguntas->pluck('id'))
            ->get()
            ->keyBy('pregunta_id');

        $total = 0;
        $maximo = 0;

        $resultado = [];

        foreach ($cuestionario->preguntas as $pregunta) {
            $respuestaEst = $respuestas[$pregunta->id] ?? null;
            $respuestaCorrecta = $pregunta->respuestas->firstWhere('es_correcta', true);

            $nota = $respuestaEst?->nota ?? null;
            $total += $nota ?? 0;
            $maximo += $pregunta->puntos;

            $resultado[] = [
                'pregunta' => $pregunta,
                'respuesta_estudiante' => $respuestaEst,
                'respuesta_correcta' => $respuestaCorrecta,
                'nota' => $nota,
            ];
        }

        return view('cuestionarios.resultado', compact('tarea', 'resultado', 'total', 'maximo'));
    }

    public function revisar(Tarea $tarea)
    {
        $this->autorizarTarea($tarea);

        $cuestionario = $tarea->cuestionario()->with('preguntas')->firstOrFail();

        $respuestas = \App\Models\RespuestaEstudiante::with(['estudiante', 'pregunta', 'respuesta'])
            ->whereIn('pregunta_id', $cuestionario->preguntas->pluck('id'))
            ->get()
            ->groupBy('usuario_id');

        return view('cuestionarios.revisar', compact('tarea', 'cuestionario', 'respuestas'));
    }

    public function guardarNotas(Request $request, Tarea $tarea)
    {
        $this->autorizarTarea($tarea);

        foreach ($request->input('notas', []) as $respuestaId => $nota) {
            $respuesta = \App\Models\RespuestaEstudiante::find($respuestaId);
            if ($respuesta && is_numeric($nota)) {
                $respuesta->nota = $nota;
                $respuesta->save();
            }
        }

        return back()->with('success', 'Notas actualizadas correctamente.');
    }

}

