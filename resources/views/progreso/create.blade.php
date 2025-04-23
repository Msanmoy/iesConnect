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

                <div class="table-responsive shadow-sm rounded">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>Estudiante</th>
                            <th>Correo</th>
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
                                <td class="text-muted">{{ $estudiante->email }}</td>
                                <td>
                                    @if ($progreso)
                                        <span class="badge bg-secondary">Nivel ya asignado: {{ ucfirst($progreso->nivel_asignado->value) }}</span>
                                    @else
                                        <div class="d-flex gap-2">
                                            <input type="hidden" name="usuario_id[]" value="{{ $estudiante->id }}">
                                            <select name="nivel_asignado[{{ $estudiante->id }}]" class="form-select form-select-sm w-auto">
                                                <option value="sencillo">Sencillo</option>
                                                <option value="intermedio">Intermedio</option>
                                                <option value="avanzado">Avanzado</option>
                                            </select>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('tareas.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-person-check me-1"></i> Guardar asignaciones
                    </button>
                </div>
            </form>
        @endif
    </div>
@endsection
