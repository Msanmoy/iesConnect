<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Mostrar el formulario de contacto
    public function show()
    {
        return view('pages.contact');
    }

    // Manejar el envío del formulario de contacto
    public function submit(Request $request)
    {
        // Aquí puedes manejar el envío del mensaje, por ejemplo, enviarlo por correo
        // $request->input('nombre'), $request->input('email'), $request->input('mensaje')

        // Redirigir o retornar una respuesta
        return redirect()->route('home')->with('message', '¡Gracias por tu mensaje!');
    }
}
