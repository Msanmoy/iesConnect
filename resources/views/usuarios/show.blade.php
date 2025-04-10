@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Detalles del Usuario</h1>

        <div class="card">
            <div class="card-body">
                <p><strong>Nombre:</strong> {{ $usuario->nombre }}</p>
                <p><strong>Email:</strong> {{ $usuario->email }}</p>
                <p><strong>Rol:</strong> {{ $usuario->rol }}</p>
            </div>
        </div>

        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary mt-3">Volver</a>
    </div>
@endsection
