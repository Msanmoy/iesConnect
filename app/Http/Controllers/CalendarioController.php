<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{
    public function eventos(Request $request)
    {
        $mes = $request->input('mes');
        $anio = $request->input('anio');

        $fechaInicio = Carbon::createFromDate($anio, $mes, 1)->startOfMonth();
        $fechaFin = Carbon::createFromDate($anio, $mes, 1)->endOfMonth();

        $tareas = Tarea::whereBetween('fecha_limite', [$fechaInicio, $fechaFin])->get();

        $eventos = [];

        foreach ($tareas as $tarea) {
            $eventos[] = [
                'id' => $tarea->id,
                'tipo' => 'tarea',
                'titulo' => $tarea->titulo,
                'descripcion' => $tarea->descripcion,
                'fecha' => Carbon::parse($tarea->fecha_limite)->format('Y-m-d'),
                'url' => route('tareas.ver.estudiante', $tarea),
            ];
        }

        return response()->json($eventos);
    }
}
