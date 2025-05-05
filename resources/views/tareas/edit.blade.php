@extends('layouts.app')

@section('title', 'Editar tarea')

@section('content')
    <div class="container-xl mt-4">
        <h2 class="mb-4">Editar Tarea</h2>

        <form action="{{ route('tareas.update', $tarea) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Título</label>
                <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $tarea->titulo) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="4">{{ old('descripcion', $tarea->descripcion) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Fecha límite</label>
                <input type="date" name="fecha_limite" class="form-control" value="{{ old('fecha_limite', $tarea->fecha_limite ? $tarea->fecha_limite->format('Y-m-d') : '') }}">
            </div>

            <hr>

            <h4 class="mt-4 mb-3">Archivos por Nivel</h4>

            @php
                $niveles = ['sencillo', 'intermedio', 'avanzado'];
            @endphp

            @foreach($niveles as $nivel)
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-capitalize">{{ $nivel }}</h5>

                        {{-- Archivos existentes --}}
                        @php
                            $archivosNivel = $tarea->archivos->where('nivel', $nivel);
                        @endphp

                        @if($archivosNivel->isNotEmpty())
                            <ul class="list-group mb-3">
                                @foreach($archivosNivel as $archivo)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $archivo->nombre_archivo }}
                                        <form action="{{ route('archivos.destroy', $archivo) }}" method="POST" onsubmit="return confirm('¿Eliminar este archivo?')" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted small">No hay archivos en este nivel.</p>
                        @endif

                        <div class="mb-2">
                            <label class="form-label">Añadir nuevos archivos</label>
                            <input type="file" name="archivos[{{ $nivel }}]" class="form-control" multiple>
                            <small class="text-muted">Puedes seleccionar varios archivos.</small>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Guardar cambios
                </button>
            </div>
        </form>
    </div>
@endsection
