@extends('layouts.app')

@section('title', 'Gestión de Asignaturas')

@section('content')
    <div class="container-xl py-4">
        <h2 class="mb-4 fw-bold">🎓 Gestión de Asignaturas</h2>

        @if(session('success'))
            <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body p-4">
                <h4 class="mb-4 text-primary"><i class="bi bi-journal-plus me-2"></i>Crear nueva asignatura</h4>
                <form method="POST" action="{{ route('admin.asignaturas.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required placeholder="Ej. Programación Web">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Profesor responsable</label>
                            <select name="usuario_id" class="form-select" required>
                                <option value="">Selecciona un profesor</option>
                                @foreach ($profesores as $profesor)
                                    <option value="{{ $profesor->id }}">{{ $profesor->nombre }} ({{ $profesor->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Código generado</label>
                                <div class="input-group">
                                    <input type="text" id="codigo" class="form-control bg-light" disabled>
                                    <button type="button" class="btn btn-outline-secondary" id="regenerarCodigo" title="Generar nuevo código">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="codigo" id="codigo_hidden">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Slug generado</label>
                            <input type="text" id="slug" class="form-control bg-light" disabled>
                            <input type="hidden" name="slug" id="slug_hidden">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3" placeholder="Ej. Laravel, Vue, etc."></textarea>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-plus-circle me-1"></i>Crear asignatura
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body p-4">
                <h4 class="mb-4 text-success"><i class="bi bi-person-plus me-2"></i>Asignar profesor a una asignatura</h4>
                <form action="{{ route('admin.asignaturas.asignar') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Asignatura</label>
                            <select name="asignatura_id" class="form-select" required>
                                @foreach ($asignaturas as $asignatura)
                                    <option value="{{ $asignatura->id }}">{{ $asignatura->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Profesor</label>
                            <select name="usuario_id" class="form-select" required>
                                @foreach ($profesores as $profesor)
                                    <option value="{{ $profesor->id }}">{{ $profesor->nombre }} ({{ $profesor->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-check2-circle me-1"></i>Asignar profesor
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="mb-3 text-secondary"><i class="bi bi-list-ul me-2"></i>Asignaturas registradas</h4>

                @if ($asignaturas->isEmpty())
                    <p class="text-muted">No hay asignaturas registradas.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach ($asignaturas as $asignatura)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $asignatura->nombre }}</strong>
                                    @if ($asignatura->profesor)
                                        <small class="text-muted d-block">👨‍🏫 {{ $asignatura->profesor->nombre }}</small>
                                    @else
                                        <small class="text-danger d-block">⚠️ Sin profesor asignado</small>
                                    @endif
                                </div>
                                <form action="{{ route('admin.asignaturas.destroy', $asignatura) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta asignatura?');">
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
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function generarCodigo() {
            const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let codigo = '';
            for (let i = 0; i < 7; i++) {
                codigo += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
            }
            return codigo;
        }

        function slugify(text) {
            return text
                .toLowerCase()
                .trim()
                .replace(/[\s\W-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const nombreInput = document.getElementById('nombre');
            const slugInput = document.getElementById('slug');
            const slugHidden = document.getElementById('slug_hidden');
            const codigoInput = document.getElementById('codigo');
            const codigoHidden = document.getElementById('codigo_hidden');
            const btnRegenerar = document.getElementById('regenerarCodigo');

            function asignarNuevoCodigo() {
                const nuevo = generarCodigo();
                codigoInput.value = nuevo;
                codigoHidden.value = nuevo;
            }

            asignarNuevoCodigo();

            nombreInput.addEventListener('input', () => {
                const slug = slugify(nombreInput.value);
                slugInput.value = slug;
                slugHidden.value = slug;
            });

            btnRegenerar.addEventListener('click', asignarNuevoCodigo);
        });
    </script>
@endpush
