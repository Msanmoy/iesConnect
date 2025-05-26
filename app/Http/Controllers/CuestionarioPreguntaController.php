<?php

namespace App\Http\Controllers;

use App\Models\Cuestionario;
use App\Models\Respuesta;
use Illuminate\Http\Request;

class CuestionarioPreguntaController extends Controller
{
    public function store(Request $request, Cuestionario $cuestionario)
    {
        $data = $request->validate([
            'nivel' => 'nullable|string|in:sencillo,intermedio,avanzado',
            'enunciado' => 'required|string',
            'puntos' => 'required|numeric|min:0',
            'respuestas' => 'required|array|min:2',
            'respuestas.*.texto' => 'required|string',
            'tipo' => 'required|in:test,abierta',
        ]);

        $pregunta = $cuestionario->preguntas()->create([
            'nivel' => $data['nivel'] ?? null,
            'enunciado' => $data['enunciado'],
            'puntos' => $data['puntos'],
            'tipo' => $request->input('tipo'),
        ]);

        $correcta = $request->input('respuestas_correcta');

        foreach ($data['respuestas'] as $i => $r) {
            $pregunta->respuestas()->create([
                'texto' => $r['texto'],
                'es_correcta' => ((string)$i === (string)$correcta),
            ]);
        }

        $cuestionario->nota_maxima = $cuestionario->preguntas()->sum('puntos');
        $cuestionario->save();

        return back()->with('success', 'Pregunta guardada correctamente.');
    }

    public function update(Request $request, $preguntaId)
    {
        $pregunta = \App\Models\Pregunta::with('respuestas')->findOrFail($preguntaId);

        $data = $request->validate([
            'enunciado' => 'required|string',
            'puntos' => 'required|numeric|min:0',
            'tipo' => 'required|in:test,abierta',
            'respuestas' => 'required|array|min:2',
            'respuestas.*.id' => 'nullable|exists:respuestas,id',
            'respuestas.*.texto' => 'required|string',
            'respuesta_correcta' => 'required',
        ]);

        $pregunta->update([
            'enunciado' => $data['enunciado'],
            'puntos' => $data['puntos'],
            'tipo' => $data['tipo'],
        ]);

        if ($data['tipo'] === 'test' && isset($data['respuestas'])) {
            foreach ($data['respuestas'] as $i => $respuestaData) {
                $respuestaData['es_correcta'] = ((string)$i === (string)$data['respuesta_correcta']);

                if (!empty($respuestaData['id'])) {
                    $respuesta = Respuesta::find($respuestaData['id']);
                    if ($respuesta->pregunta_id === $pregunta->id) {
                        $respuesta->update($respuestaData);
                    }
                } else {
                    $pregunta->respuestas()->create($respuestaData);
                }
            }
        } else {
            $pregunta->respuestas()->delete();
        }

        return back()->with('success', 'Pregunta actualizada correctamente.');
    }

    public function destroy($preguntaId)
    {
        $pregunta = \App\Models\Pregunta::findOrFail($preguntaId);
        $pregunta->delete();

        return back()->with('success', 'Pregunta eliminada correctamente.');
    }

    public function reordenar(Request $request)
    {
        $data = $request->validate([
            'orden' => 'required|array',
            'orden.*.id' => 'required|exists:preguntas,id',
            'orden.*.posicion' => 'required|integer',
        ]);

        foreach ($data['orden'] as $item) {
            \App\Models\Pregunta::where('id', $item['id'])->update(['orden' => $item['posicion']]);
        }

        return response()->json(['message' => 'Orden actualizado correctamente.']);
    }

}
