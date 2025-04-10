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
        <div class="card shadow rounded-4">
            <div class="card-body">
                <h2 class="card-title mb-4">👤 Mi Perfil</h2>

                <dl class="row">
                    <dt class="col-sm-3">Nombre</dt>
                    <dd class="col-sm-9">{{ Auth::user()->nombre }}</dd>

                    <dt class="col-sm-3">Email</dt>
                    <dd class="col-sm-9">{{ Auth::user()->email }}</dd>

                    <dt class="col-sm-3">Rol</dt>
                    <dd class="col-sm-9">{{ Auth::user()->rol }}</dd>
                </dl>
            </div>
        </div>
    </div>
@endsection
