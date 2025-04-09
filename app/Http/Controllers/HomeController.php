<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Crea una nueva instancia del controlador.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Muestra el panel principal de la aplicación.
     */
    public function index(): View
    {
        return view('home');
    }
}
