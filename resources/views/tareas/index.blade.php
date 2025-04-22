@extends('layouts.app')

@section('title', 'Mis Tareas')

@section('content')
    <div class="container-xl">
        <h2 class="mb-4">Mis Tareas</h2>

        @forelse ($tareas as $tarea)
            @php
                $progreso = $tarea->progresos->firstWhere('usuario_id', auth()->id());
            @endphp

            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">{{ $tarea->titulo }}</h4>
                    <p class="text-muted mb-1">{{ $tarea->asignatura->nombre }}</p>
                    <p>{{ $tarea->descripcion }}</p>
                    <p><strong>Fecha límite:</strong> {{ \Carbon\Carbon::parse($tarea->fecha_limite)->format('d/m/Y') }}</p>

                    @if ($progreso)
                        <p class="mb-2">
                            <strong>Nivel asignado:</strong>
                            <span class="badge bg-primary text-uppercase">{{ $progreso->nivel_asignado->value }}</span>
                        </p>

                        <div class="list-group">
                            @foreach (['sencillo', 'intermedio', 'avanzado'] as $nivel)
                                @php
                                    $estado = $progreso->{'entregado_'.$nivel};
                                    $puedeEntregar = match($nivel) {
                                        'sencillo' => $progreso->nivel_asignado->value === 'sencillo',
                                        'intermedio' => $progreso->nivel_asignado->value !== 'avanzado' && $progreso->entregado_sencillo,
                                        'avanzado' => (
                                            $progreso->nivel_asignado->value === 'avanzado' ||
                                            ($progreso->nivel_asignado->value === 'intermedio' && $progreso->entregado_intermedio) ||
                                            ($progreso->nivel_asignado->value === 'sencillo' && $progreso->entregado_intermedio)
                                        )
                                    };
                                @endphp

                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="text-capitalize">{{ $nivel }}</strong>
                                        @if ($estado)
                                            <span class="badge bg-success ms-2">Entregado</span>
                                        @endif
                                    </div>

                                    @if (!$estado && $puedeEntregar)
                                        <form action="{{ route('entregas.store', $progreso) }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
                                            @csrf
                                            <input type="hidden" name="nivel" value="{{ $nivel }}">
                                            <input type="file" name="archivo" class="form-control form-control-sm" required>
                                            <button type="submit" class="btn btn-primary btn-sm">Subir</button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if ($progreso->entregas->count())
                            <div class="mt-3">
                                <h6>Mis Entregas</h6>
                                <ul class="list-unstyled">
                                    @foreach ($progreso->entregas as $entrega)
                                        <li>
                                            <i class="bi bi-file-earmark-arrow-down"></i>
                                            <a href="{{ asset('storage/' . $entrega->archivo) }}" target="_blank">
                                                {{ ucfirst($entrega->nivel) }} - {{ \Carbon\Carbon::parse($entrega->fecha_entrega)->format('d/m/Y H:i') }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning mt-3">No tienes asignado un nivel para esta tarea.</div>
                    @endif
                </div>
            </div>
        @empty
            <div class="alert alert-info">No tienes tareas asignadas actualmente.</div>
        @endforelse
    </div>
@endsection
