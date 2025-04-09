<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AsignaturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asignaturas = Asignatura::with(['tareas' => function($query) {
            $query->where('visible', true)
                ->where('eliminado', false)
                ->orderBy('created_at', 'desc');
        }])->get();

        return view('clases.asignaturas', compact('asignaturas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('asignaturas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:asignaturas',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $asignatura = new Asignatura();
        $asignatura->nombre = $request->nombre;
        $asignatura->slug = Str::slug($request->nombre);
        $asignatura->imagen = $this->manejarImagen($request);

        $asignatura->save();

        return redirect()->route('asignaturas.index')
            ->with('success', 'Asignatura creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $asignatura = Asignatura::where('slug', $slug)->firstOrFail();
        return view('clases.asignatura', compact('asignatura'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asignatura $asignatura)
    {
        return view('asignaturas.edit', compact('asignatura'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asignatura $asignatura)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:asignaturas,nombre,' . $asignatura->id,
            'imagen' => 'nullable|image|max:2048',
        ]);

        $asignatura->nombre = $request->nombre;
        $asignatura->slug = Str::slug($request->nombre);
        $asignatura->imagen = $this->manejarImagen($request, $asignatura);

        $asignatura->save();

        return redirect()->route('asignaturas.index')
            ->with('success', 'Asignatura actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asignatura $asignatura)
    {
        // Verificar si hay clases asociadas antes de eliminar
        if ($asignatura->clases()->count() > 0) {
            return redirect()->route('asignaturas.index')
                ->with('error', 'No se puede eliminar la asignatura porque tiene clases asociadas.');
        }

        // Eliminar imagen si existe
        if ($asignatura->imagen) {
            Storage::delete('public/' . $asignatura->imagen);
        }

        $asignatura->delete();

        return redirect()->route('asignaturas.index')
            ->with('success', 'Asignatura eliminada correctamente.');
    }

    /**
     * Maneja la subida y eliminación de imágenes.
     */
    private function manejarImagen(Request $request, ?Asignatura $asignatura = null): ?string
    {
        if (!$request->hasFile('imagen')) {
            return $asignatura?->imagen;
        }

        if ($asignatura?->imagen) {
            Storage::delete('public/' . $asignatura->imagen);
        }

        $path = $request->file('imagen')->store('public/images/asignaturas');
        return str_replace('public/', '', $path);
    }
}
