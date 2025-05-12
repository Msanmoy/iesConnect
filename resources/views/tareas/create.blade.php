@extends('layouts.app')

@section('title', 'Crear publicación')

@section('content')
    <div class="container-xl">
        <h2 class="mb-4">Crear publicación</h2>

        <form action="{{ route('tareas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="asignatura_id" value="{{ $asignatura->id }}">
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de publicación</label>
                <select name="tipo" id="tipo" class="form-select" required>
                    <option value="tarea">Tarea</option>
                    <option value="cuestionario">Tarea de cuestionario</option>
                    <option value="pregunta">Pregunta</option>
                    <option value="material">Material</option>
                    <option value="reutilizar">Reutilizar publicación</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" name="titulo" id="titulo" class="form-control" required>
            </div>

            <div class="mb-3" id="descripcion-section">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="4" class="form-control"></textarea>
            </div>

            <div id="niveles-section">
                <label class="form-label">Archivos por nivel</label>
                @foreach(['sencillo', 'intermedio', 'avanzado'] as $nivel)
                    <div class="mb-2">
                        <label for="archivo_{{ $nivel }}" class="form-label">{{ ucfirst($nivel) }}</label>
                        <input type="file" name="archivos[{{ $nivel }}][]" multiple class="form-control">
                    </div>
                @endforeach
            </div>

            <div id="fecha-limite-section" class="mb-3">
                <label for="fecha_limite" class="form-label">Fecha límite</label>
                <input type="date" name="fecha_limite" id="fecha_limite" class="form-control">
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Crear
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tipo = document.getElementById('tipo')
            const niveles = document.getElementById('niveles-section')
            const fechaLimite = document.getElementById('fecha-limite-section')
            const descripcion = document.getElementById('descripcion-section')

            function toggleSections() {
                const value = tipo.value
                niveles.style.display = (value === 'tarea') ? 'block' : 'none'
                fechaLimite.style.display = (value === 'tarea' || value === 'cuestionario') ? 'block' : 'none'
                descripcion.style.display = 'block';

            }

            tipo.addEventListener('change', toggleSections)
            toggleSections()
        })
    </script>
@endpush
