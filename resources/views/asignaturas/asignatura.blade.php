@extends('layouts.app')

@section('title', 'Mis Asignaturas')

@push('header-actions')
    <form action="{{ route('asignaturas.unirse') }}" method="POST" class="d-flex align-items-center">
        @csrf
        <input type="text" name="codigo_clase" class="form-control me-2" placeholder="Código de clase">
        <button class="btn btn-primary">Unirse</button>
    </form>
@endpush

@section('content')
    <div class="container-xl">
        <h1 class="mb-4">Mis Clases</h1>

        @if ($asignaturas->isEmpty())
            <div class="alert alert-info">
                No estás inscrito en ninguna clase.
            </div>
        @else
            <div class="row">
                @foreach ($asignaturas as $asignatura)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ asset('images/' . $asignatura->nombre . '.jpg') }}" class="card-img-top" alt="Imagen de la asignatura">
                            <div class="card-body">
                                <h5 class="card-title">{{ $asignatura->nombre }}</h5>
                                <p class="card-text">{{ Str::limit($asignatura->descripcion, 100) }}</p>
                                <a href="{{ route('asignaturas.show', $asignatura->slug) }}" class="btn btn-outline-primary w-100">
                                    Ver clase
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
