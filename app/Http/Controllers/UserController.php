<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    public function index()
    {

        $usuario = auth()->user();

        $asignaturas = $usuario->aulas
            ->map(fn($aula) => $aula->clase?->asignatura)
            ->filter()
            ->unique('id')
            ->values();

        return view('clases.asignaturas', compact('asignaturas'));
    }


}
