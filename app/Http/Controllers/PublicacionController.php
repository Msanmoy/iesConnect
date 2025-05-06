<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Publicacion;
use App\Notifications\NewClassMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicacionController extends Controller
{
    public function store(Request $request, Asignatura $asignatura)
    {
        $request->validate([
            'contenido' => 'required|string|max:1000',
        ]);

        $usuario = Auth::user();

        if (!$asignatura->usuarios->contains($usuario->id) && $asignatura->profesor->id !== $usuario->id) {
            abort(403, 'No estás inscrito en esta asignatura');
        }

        Publicacion::create([
            'usuario_id' => $usuario->id,
            'asignatura_id' => $asignatura->id,
            'contenido' => $request->contenido,
        ]);

        foreach ($asignatura->estudiantes as $estudiante) {
            $estudiante->notify(new NewClassMessageNotification(
                'Se ha publicado un nuevo mensaje en: ' . $asignatura->nombre,
                route('asignaturas.show', $asignatura->slug),
            ));
        }

        return redirect()->route('asignaturas.show', $asignatura->slug)->with('success', 'Publicación enviada correctamente.');
    }

    public function destroy(Publicacion $publicacion)
    {
        // Solo el profesor que la creó puede borrarla
        if ($publicacion->usuario_id === auth()->id()) {
            $slug = $publicacion->asignatura->slug;
            $publicacion->delete();

            return redirect()->route('asignaturas.show', $slug)->with('success', 'Publicación eliminada.');
        } else {
            $slug = $publicacion->asignatura->slug;
            return redirect()->route('asignaturas.show', $slug)->with('failure', 'No tienes permiso.');
        }


    }

    public function update(Request $request, Publicacion $publicacion)
    {
        $request->validate([
            'contenido' => 'required|string|max:1000',
        ]);

        if ($publicacion->usuario_id === auth()->id()) {
            $publicacion->update([
                'contenido' => $request->contenido,
            ]);

            return redirect()->route('asignaturas.show', $publicacion->asignatura->slug)->with('success', 'Publicación actualizada.');
        } else {
            return redirect()->route('asignaturas.show', $publicacion->asignatura->slug)->with('failure', 'No tienes permiso.');
        }

    }

}

