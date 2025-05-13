@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')
    <div class="container-xl">
        <h2 class="mb-4">📊 Panel de Administración</h2>

        <div class="row g-4">
            <div class="col-lg-6">
                <a href="{{ route('admin.usuarios') }}" class="btn btn-outline-primary w-100 mb-3">
                    👨‍🏫 Gestionar profesores
                </a>
                <div class="card shadow-sm p-4">
                    <h5 class="mb-3">Usuarios Registrados</h5>
                    <canvas id="usuariosChart" height="200"></canvas>
                </div>
            </div>

            <div class="col-lg-6">
                <a href="{{ route('admin.asignaturas') }}" class="btn btn-outline-secondary w-100 mb-3">
                    📘 Gestionar asignaturas
                </a>
                <div class="card shadow-sm p-4">
                    <h5 class="mb-3">Tareas por asignatura</h5>
                    <canvas id="tareasChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const usuariosCtx = document.getElementById('usuariosChart').getContext('2d');
            const tareasCtx = document.getElementById('tareasChart').getContext('2d');

            new Chart(usuariosCtx, {
                type: 'pie',
                data: {
                    labels: @json($usuarios->keys()),
                    datasets: [{
                        data: @json($usuarios->values()),
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                }
            });

            new Chart(tareasCtx, {
                type: 'pie',
                data: {
                    labels: @json($tareasPorAsignatura->keys()),
                    datasets: [{
                        label: 'Cantidad de tareas',
                        data: @json($tareasPorAsignatura->values()),
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
@endpush
