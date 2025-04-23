@extends('layouts.app')

@section('title', $tarea->titulo)

@section('content')
    <div class="container-xl">
        <h2 class="mb-3">{{ $tarea->titulo }}</h2>

        <p class="mb-3">{{ $tarea->descripcion }}</p>

        <p>
            <strong>Fecha límite:</strong>
            {{ \Carbon\Carbon::parse($tarea->fecha_limite)->format('d/m/Y') }}
        </p>

        @if ($tarea->archivos->count())
            <div class="mb-4">
                <h5>Archivos proporcionados por el profesor</h5>
                <ul class="list-group">
                    @foreach ($tarea->archivos as $archivo)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $archivo->nombre_archivo }}</span>
                            <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                Ver
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <hr>

        <h5 class="mb-3">Subir tu entrega</h5>

        @php
            $nivel = $progreso->nivel_asignado->value;
            $niveles = ['sencillo', 'intermedio', 'avanzado'];
            $puedeEntregar = false;

            switch ($nivel) {
                case 'sencillo':
                    $puedeEntregar = !$progreso->entregado_sencillo;
                    $nivelActual = 'sencillo';
                    break;
                case 'intermedio':
                    if (!$progreso->entregado_intermedio) {
                        $puedeEntregar = true;
                        $nivelActual = 'intermedio';
                    }
                    break;
                case 'avanzado':
                    if (!$progreso->entregado_avanzado) {
                        $puedeEntregar = true;
                        $nivelActual = 'avanzado';
                    }
                    break;
            }
        @endphp

        @if ($puedeEntregar ?? false)
            <form action="{{ route('entregas.store', $progreso) }}" method="POST" enctype="multipart/form-data" class="card shadow-sm p-4 mb-4">
                @csrf
                <input type="hidden" name="nivel" value="{{ $nivelActual }}">

                <div class="mb-3">
                    <label for="archivo" class="form-label">Selecciona tu archivo</label>
                    <input type="file" name="archivo" id="archivo" class="form-control" required>
                    <small class="text-muted">Archivos permitidos: PDF, imágenes, documentos. Máx. 20MB.</small>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i> Subir entrega ({{ ucfirst($nivelActual) }})
                    </button>
                </div>
            </form>
        @else
            <div class="alert alert-success">
                Ya has entregado tu trabajo para el nivel actual ({{ ucfirst($nivel) }}).
            </div>
        @endif

        @if ($progreso->entregas->count())
            <h5>Historial de entregas</h5>
            <ul class="list-unstyled">
                @foreach ($progreso->entregas as $entrega)
                    <li>
                        <i class="bi bi-file-earmark-arrow-down"></i>
                        <a href="{{ asset('storage/' . $entrega->archivo) }}" target="_blank">
                            {{ ucfirst($entrega->nivel) }} – {{ \Carbon\Carbon::parse($entrega->fecha_entrega)->format('d/m/Y H:i') }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="mt-4">
            <a href="{{ route('tareas.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver a mis tareas
            </a>
        </div>
    </div>
@endsection
