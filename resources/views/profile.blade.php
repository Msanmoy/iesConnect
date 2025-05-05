@extends('layouts.app')

@section('title', 'Mi perfil')

@push('header-actions')
    <a href="{{ route('logout') }}" class="btn btn-danger btn-sm"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Cerrar sesión
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
@endpush

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded-4 p-4">
            <div class="d-flex align-items-center mb-4">
                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center me-3" style="width: 70px; height: 70px; font-size: 28px; font-weight: bold;">
                    {{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}
                </div>

                <div>
                    <h3 class="mb-0">{{ Auth::user()->nombre }}</h3>
                    <small class="text-muted">{{ ucfirst(strtolower(Auth::user()->rol)) }}</small>
                </div>
            </div>

            <hr>

            <dl class="row mb-0">
                <dt class="col-sm-3">Nombre completo</dt>
                <dd class="col-sm-9">{{ Auth::user()->nombre }}</dd>

                <dt class="col-sm-3">Correo electrónico</dt>
                <dd class="col-sm-9">{{ Auth::user()->email }}</dd>

                <dt class="col-sm-3">Rol</dt>
                <dd class="col-sm-9">{{ ucfirst(strtolower(Auth::user()->rol)) }}</dd>
            </dl>
        </div>
    </div>
@endsection
