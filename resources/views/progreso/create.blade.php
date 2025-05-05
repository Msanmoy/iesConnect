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
                                <input type="hidden" name="usuario_id[]" value="{{ $estudiante->id }}">

                                @if ($progreso)
                                    <input type="hidden" name="progreso_id[{{ $estudiante->id }}]" value="{{ $progreso->id }}">
                                    <select name="nivel_asignado[{{ $estudiante->id }}]" class="form-select form-select-sm w-auto d-inline-block">
                                        <option value="sencillo" {{ $progreso->nivel_asignado->value === 'sencillo' ? 'selected' : '' }}>Sencillo</option>
                                        <option value="intermedio" {{ $progreso->nivel_asignado->value === 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                                        <option value="avanzado" {{ $progreso->nivel_asignado->value === 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                                    </select>
                                @else
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
                    <a href="{{ route('tareas.show', $tarea) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-circle me-1"></i> Volver atrás
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Asignar niveles
                    </button>
                </div>
            </form>

        @endif
    </div>
@endsection
