@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Crear Usuario</h1>

        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf
            @include('usuarios.form')
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection
