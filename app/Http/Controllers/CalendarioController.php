<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarioController extends Controller
{
    /**
     * Mostrar la vista del calendario con los eventos del usuario.
     */
    public function index()
    {
        $usuario = Auth::user();
        $mes = request('mes', date('m'));
        $anio = request('anio', date('Y'));

        $eventos = $this->obtenerEventosPorFecha($usuario->id, $anio, $mes);

        return view('calendario.index', compact('eventos', 'mes', 'anio'));
    }

    /**
     * Obtener eventos en formato JSON para actualizar el calendario dinámicamente.
     */
    public function eventos(Request $request)
    {
        $user = Auth::user();

        $mes = $request->query('mes');
        $anio = $request->query('anio');

        $eventos = Evento::where('usuario_id', $user->id)
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->get();

        return response()->json($eventos);
    }


    /**
     * Filtrar eventos por tipo y/o asignatura.
     */
    public function filtrarEventos(Request $request)
    {
        $usuario = Auth::user();

        $eventos = Evento::where('usuario_id', $usuario->id)
            ->whereYear('fecha', $request->input('anio', date('Y')))
            ->whereMonth('fecha', $request->input('mes', date('m')))
            ->when($request->filled('tipo') && $request->input('tipo') !== 'todos', function ($query) use ($request) {
                $query->where('tipo', $request->input('tipo'));
            })
            ->when($request->filled('asignatura_id') && $request->input('asignatura_id') !== 'todas', function ($query) use ($request) {
                $query->where('asignatura_id', $request->input('asignatura_id'));
            })
            ->orderBy('fecha')
            ->get();

        return response()->json($eventos);
    }

    /**
     * Obtiene los eventos de un usuario para un mes y año determinados.
     */
    private function obtenerEventosPorFecha(int $usuarioId, string $anio, string $mes)
    {
        return Evento::where('usuario_id', $usuarioId)
            ->whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes)
            ->orderBy('fecha')
            ->get();
    }
}
