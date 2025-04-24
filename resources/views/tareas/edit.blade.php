@extends('layouts.app')

@section('title', 'Editar tarea')

@section('content')
    <div class="container-xl">
        <h2 class="mb-4">Editar tarea: <span class="text-muted">{{ $tarea->titulo }}</span></h2>

        <form action="{{ route('tareas.update', $tarea) }}" method="POST" enctype="multipart/form-data" class="card shadow-sm p-4">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="asignatura_id" class="form-label">Asignatura</label>
                <select name="asignatura_id" id="asignatura_id" class="form-select" required>
                    @foreach ($asignaturas as $asignatura)
                        <option value="{{ $asignatura->id }}" {{ $tarea->asignatura_id == $asignatura->id ? 'selected' : '' }}>
                            {{ $asignatura->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" name="titulo" id="titulo" class="form-control" required value="{{ old('titulo', $tarea->titulo) }}">
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="4" class="form-control">{{ old('descripcion', $tarea->descripcion) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="fecha_limite" class="form-label">Fecha límite</label>
                <input type="date" name="fecha_limite" id="fecha_limite" class="form-control" value="{{ old('fecha_limite', $tarea->fecha_limite->format('Y-m-d')) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Archivos adjuntos actuales</label>
                @if ($tarea->archivos->isEmpty())
                    <p class="text-muted">No hay archivos adjuntos.</p>
                @else
                    <ul class="list-group mb-2">
                        @foreach ($tarea->archivos as $archivo)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $archivo->nombre_archivo }}</span>
                                <form action="{{ route('archivos.destroy', $archivo) }}" method="POST" onsubmit="return confirm('¿Eliminar este archivo?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>

                @endif
            </div>

            <div class="mb-3">
                <label for="archivos" class="form-label">Agregar nuevos archivos</label>
                <input type="file" name="archivos[]" id="archivos" class="form-control" multiple>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('tareas.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Guardar cambios
                </button>
            </div>
        </form>
    </div>
@endsection
