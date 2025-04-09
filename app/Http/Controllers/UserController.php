<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Mostrar asignaturas únicas relacionadas con las aulas del usuario autenticado.
     */
    public function index()
    {
        $usuario = auth()->user();

        $asignaturas = $this->obtenerAsignaturasDelUsuario($usuario);

        return view('clases.asignaturas', compact('asignaturas'));
    }

    /**
     * Obtener asignaturas únicas desde las aulas del usuario.
     */
    private function obtenerAsignaturasDelUsuario($usuario)
    {
        return $usuario->aulas
            ->map(fn($aula) => $aula->clase?->asignatura)
            ->filter()
            ->unique('id')
            ->values();
    }
}
