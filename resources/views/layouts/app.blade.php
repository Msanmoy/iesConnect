<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IESConnect')</title>

    <!-- Bootstrap y estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .floating-img {
            margin-top: -100px;
            position: relative;
            z-index: 1;
        }
        body {
            font-family: 'Cabin', sans-serif;
        }
    </style>

    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container-xl d-flex align-items-center">
        <button class="navbar-toggler d-lg-none border-0 shadow-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a class="navbar-brand d-none d-lg-block" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="IES Vega de Mijas" style="height: 80px;">
        </a>

        <div class="collapse navbar-collapse d-none d-lg-flex" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clases.asignaturas') }}">Clases</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('calendario') }}">Calendario</a>
                </li>
            </ul>
        </div>

        <div class="d-flex align-items-center border-start ms-auto">

            @stack('header-actions')

            <button class="btn me-3 border-0">
                <i class="bi bi-bell"></i>
            </button>
            <div class="dropdown">
                @auth
                    <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->nombre_completo }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile') }}">Perfil</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                @else
                    <a href="{{ route('login') }}">
                        <button class="btn btn-primary">Iniciar Sesión</button>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Sidebar -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menú</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('clases.asignaturas') }}">Clases</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('calendario') }}">Calendario</a>
            </li>
        </ul>
    </div>
</div>

<!-- Contenido -->
<main class="flex-grow-1 py-4">
    @yield('content')
</main>

<!-- Footer -->
<footer class="bg-dark text-white py-4">
    <div class="container-xl text-center">
        <div class="row">
            <div class="col-md-4">
                <h5>IES Vega de Mijas</h5>
                <p class="small">Tu plataforma educativa para una enseñanza sin límites.</p>
            </div>
            <div class="col-md-4">
                <h5>Enlaces rápidos</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}" class="text-white text-decoration-none small">Inicio</a></li>
                    <li><a href="{{ route('clases.asignaturas') }}" class="text-white text-decoration-none small">Clases</a></li>
                    <li><a href="{{ route('contact') }}" class="text-white text-decoration-none small">Contacto</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Síguenos</h5>
                <a href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
                <a href="#" class="text-white me-2"><i class="bi bi-twitter"></i></a>
                <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
            </div>
        </div>
        <p class="mt-3 small">&copy; {{ date('Y') }} IESConnect. Todos los derechos reservados.</p>
    </div>
</footer>

<!-- Scripts -->
<script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
@stack('scripts')
</body>
</html>
