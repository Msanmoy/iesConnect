<?php

namespace App\Http\Controllers;

use App\Models\ArchivoTarea;
use Illuminate\Support\Facades\Storage;

class ArchivoTareaController extends Controller
{
    public function destroy(ArchivoTarea $archivo)
    {
        if ($archivo->tarea->asignatura->usuario_id !== auth()->id()) {
            abort(403);
        }

        if (Storage::disk('public')->exists($archivo->ruta_archivo)) {
            Storage::disk('public')->delete($archivo->ruta_archivo);
        }

        $archivo->delete();

        return back()->with('success', 'Archivo eliminado correctamente.');
    }
}
