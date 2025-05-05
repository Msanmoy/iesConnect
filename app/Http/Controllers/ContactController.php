<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show()
    {
        return view('pages.contact');
    }

    public function submit(Request $request)
    {
        return redirect()->route('home')->with('message', '¡Gracias por tu mensaje!');
    }
}
