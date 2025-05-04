@extends('layouts.app')

@section('title', 'Crear Nueva Tarea')

@section('content')
    <div class="container-xl mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h2 class="mb-4">Nueva tarea para <span class="text-primary">{{ $asignatura->nombre }}</span></h2>

                <form action="{{ route('tareas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="asignatura_id" value="{{ $asignatura->id }}">

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required>{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_limite" class="form-label">Fecha Límite</label>
                        <input type="date" class="form-control" id="fecha_limite" name="fecha_limite" value="{{ old('fecha_limite') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="archivos" class="form-label">Archivos adjuntos (opcional)</label>
                        <input type="file" class="form-control" id="archivos" name="archivos[]" multiple>
                        <small class="text-muted">Puedes subir varios archivos (máx. 20MB cada uno)</small>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary rounded-2 px-4">
                            <i class="bi bi-save me-1"></i> Crear tarea
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
