@extends('layouts.app')

@section('title', 'Asignar niveles')

@section('content')
    <div class="container-xl">
        <h2 class="mb-4">Asignar niveles a estudiantes – <span class="text-muted">{{ $tarea->titulo }}</span></h2>

        @if ($estudiantes->isEmpty())
            <div class="alert alert-warning">No hay estudiantes asignados a esta asignatura.</div>
        @else
            <form action="{{ route('progreso.store', $tarea) }}" method="POST">
                @csrf

                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Nivel asignado</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($estudiantes as $estudiante)
                        @php
                            $progreso = $tarea->progresos->firstWhere('usuario_id', $estudiante->id);
                        @endphp
                        <tr>
                            <td>{{ $estudiante->nombre }}</td>
                            <td>
                                @if ($progreso)
                                    <span class="badge bg-secondary">
                                Ya asignado: {{ ucfirst($progreso->nivel_asignado->value) }}
                            </span>
                                @else
                                    <input type="hidden" name="usuario_id[]" value="{{ $estudiante->id }}">
                                    <select name="nivel_asignado[{{ $estudiante->id }}]" class="form-select form-select-sm w-auto d-inline-block">
                                        <option value="sencillo">Sencillo</option>
                                        <option value="intermedio">Intermedio</option>
                                        <option value="avanzado">Avanzado</option>
                                    </select>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Asignar niveles
                    </button>
                </div>
            </form>

        @endif
    </div>
@endsection
