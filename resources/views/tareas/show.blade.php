@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $tarea->titulo }}</h1>
        <p>{{ $tarea->descripcion }}</p>
    </div>
@endsection
