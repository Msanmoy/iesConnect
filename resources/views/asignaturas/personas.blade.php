@extends('layouts.app')

@section('title', 'Personas - ' . $asignatura->nombre)

@section('content')
    <div class="container-xl mt-4">

        @include('asignaturas.partials.navegacion', ['asignatura' => $asignatura])

        @include('asignaturas.partials.banner', ['asignatura' => $asignatura])

        <div class="row mt-4">

            <div class="col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center me-3" style="width: 60px; height: 60px; font-size: 24px;">
                            {{ strtoupper(substr($profesor->nombre, 0, 1)) }}
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $profesor->nombre }}</h5>
                            <small class="text-muted">Profesor</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Estudiantes ({{ $alumnos->count() }})</h5>
                        <div class="row">
                            @foreach($alumnos as $alumno)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="d-flex align-items-center border rounded p-2 shadow-sm">
                                        <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center me-3" style="width: 45px; height: 45px; font-size: 18px;">
                                            {{ strtoupper(substr($alumno->nombre, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $alumno->nombre }}</strong><br>
                                            <small class="text-muted">{{ $alumno->email }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if($alumnos->isEmpty())
                                <p class="text-muted">No hay estudiantes aún en esta clase.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
