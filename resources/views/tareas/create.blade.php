@extends('layouts.app')

@section('title', 'Crear tarea')

@section('content')
    <div class="container-xl">
        <h2 class="mb-4">Crear nueva tarea</h2>

        <form action="{{ route('tareas.store') }}" method="POST" enctype="multipart/form-data" class="card shadow-sm p-4">
            @csrf

            <div class="mb-3">
                <label for="asignatura_id" class="form-label">Asignatura</label>
                <select name="asignatura_id" id="asignatura_id" class="form-select" required>
                    <option value="">Selecciona una asignatura</option>
                    @foreach ($asignaturas as $asignatura)
                        <option value="{{ $asignatura->id }}" {{ old('asignatura_id') == $asignatura->id ? 'selected' : '' }}>
                            {{ $asignatura->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" name="titulo" id="titulo" class="form-control" required value="{{ old('titulo') }}">
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="4" class="form-control">{{ old('descripcion') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="fecha_limite" class="form-label">Fecha límite</label>
                <input type="date" name="fecha_limite" id="fecha_limite" class="form-control" value="{{ old('fecha_limite') }}">
            </div>

            <div class="mb-3">
                <label for="archivos" class="form-label">Archivos adjuntos</label>
                <input type="file" name="archivos[]" id="archivos" class="form-control" multiple>
                <small class="text-muted">Puedes adjuntar varios archivos (máx. 20MB c/u).</small>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('tareas.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Crear tarea
                </button>
            </div>
        </form>
    </div>
@endsection
