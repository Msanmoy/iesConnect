@extends('layouts.app')

@section('title', 'Crear publicación')

@section('content')
    <div class="container-xl py-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h3 class="card-title mb-4">
                    <i class="bi bi-pencil-square me-2"></i>Crear nueva publicación
                </h3>

                <form action="{{ route('tareas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="asignatura_id" value="{{ $asignatura->id }}">

                    <div class="mb-4">
                        <label for="tipo" class="form-label fw-semibold">Tipo de publicación</label>
                        <select name="tipo" id="tipo" class="form-select" required>
                            <option value="tarea">Tarea</option>
                            <option value="cuestionario">Tarea de cuestionario</option>
                        </select>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="generica" id="generica">
                        <label class="form-check-label fw-semibold" for="generica">¿Tarea genérica (sin niveles)?</label>
                    </div>

                    <div class="mb-4">
                        <label for="titulo" class="form-label fw-semibold">Título de la publicación</label>
                        <input type="text" name="titulo" id="titulo" class="form-control" required placeholder="Ej. Proyecto final sobre Laravel">
                    </div>

                    <div class="mb-4" id="descripcion-section">
                        <label for="descripcion" class="form-label fw-semibold">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="4" class="form-control" placeholder="Describe aquí los objetivos, instrucciones o criterios de evaluación..."></textarea>
                    </div>

                    <div id="archivos-nivel-section" class="mb-4">
                        <label class="form-label fw-semibold">Archivos por nivel</label>
                        @foreach(['sencillo', 'intermedio', 'avanzado'] as $nivel)
                            <div class="mb-2">
                                <label for="archivo_{{ $nivel }}" class="form-label">{{ ucfirst($nivel) }}</label>
                                <input type="file" name="archivos[{{ $nivel }}][]" multiple class="form-control">
                            </div>
                        @endforeach
                    </div>

                    <div id="archivos-genericos-section" class="mb-4" style="display: none;">
                        <label for="archivos_genericos" class="form-label fw-semibold">Archivos para todos los estudiantes</label>
                        <input type="file" name="archivos_genericos[]" multiple class="form-control">
                    </div>

                    <div id="fecha-limite-section" class="mb-4">
                        <label for="fecha_limite" class="form-label fw-semibold">Fecha límite de entrega</label>
                        <input type="date" name="fecha_limite" id="fecha_limite" class="form-control">
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-send-check me-2"></i>Publicar
                        </button>
                    </div>
                </form>

                @if ($errors->any())
                    <div class="alert alert-danger mt-4">
                        <h6 class="mb-2">Errores detectados:</h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tipo = document.getElementById('tipo')
            const generica = document.getElementById('generica')
            const niveles = document.getElementById('archivos-nivel-section')
            const genericos = document.getElementById('archivos-genericos-section')
            const fechaLimite = document.getElementById('fecha-limite-section')
            const descripcion = document.getElementById('descripcion-section')

            function toggleSections() {
                const tipoValue = tipo.value
                const isGenerica = generica.checked

                niveles.style.display = (!isGenerica && tipoValue === 'tarea') ? 'block' : 'none'
                genericos.style.display = (isGenerica && tipoValue === 'tarea') ? 'block' : 'none'
                fechaLimite.style.display = (tipoValue === 'tarea' || tipoValue === 'cuestionario') ? 'block' : 'none'
                descripcion.style.display = 'block'
            }

            tipo.addEventListener('change', toggleSections)
            generica.addEventListener('change', toggleSections)
            toggleSections()
        })
    </script>
@endpush
