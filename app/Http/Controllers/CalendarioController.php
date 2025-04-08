<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarioController extends Controller
{
    /**
     * Mostrar la vista del calendario con los eventos del usuario.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtener el usuario autenticado
        $usuario = Auth::user();

        // Obtener el mes y año actual o los proporcionados en la solicitud
        $mes = request('mes', date('m'));
        $anio = request('anio', date('Y'));

        // Obtener todos los eventos del usuario para el mes seleccionado
        $eventos = Evento::where('usuario_id', $usuario->id)
            ->whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes)
            ->orderBy('fecha')
            ->get();

        return view('calendario.index', [
            'eventos' => $eventos,
            'mes' => $mes,
            'anio' => $anio
        ]);
    }

    /**
     * Obtener eventos en formato JSON para actualizar el calendario dinámicamente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEventos(Request $request)
    {
        $usuario = Auth::user();
        $mes = $request->input('mes');
        $anio = $request->input('anio');

        $eventos = Evento::where('usuario_id', $usuario->id)
            ->whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes)
            ->orderBy('fecha')
            ->get();

        return response()->json($eventos);
    }

    /**
     * Filtrar eventos por tipo y/o asignatura.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filtrarEventos(Request $request)
    {
        $usuario = Auth::user();
        $tipo = $request->input('tipo');
        $asignaturaId = $request->input('asignatura_id');
        $mes = $request->input('mes', date('m'));
        $anio = $request->input('anio', date('Y'));

        $query = Evento::where('usuario_id', $usuario->id)
            ->whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes);

        if ($tipo && $tipo !== 'todos') {
            $query->where('tipo', $tipo);
        }

        if ($asignaturaId && $asignaturaId !== 'todas') {
            $query->where('asignatura_id', $asignaturaId);
        }

        $eventos = $query->orderBy('fecha')->get();

        return response()->json($eventos);
    }
}
