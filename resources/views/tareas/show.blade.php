@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $tarea->titulo }}</h1>
        <p>{{ $tarea->descripcion }}</p>

        <h4>Archivos adjuntos:</h4>
        <ul>
            @foreach($tarea->archivos as $archivo)
                <li>
                    <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}" target="_blank">
                        {{ $archivo->nombre_archivo }}
                    </a>
                </li>
            @endforeach
        </ul>

    </div>

@endsection
