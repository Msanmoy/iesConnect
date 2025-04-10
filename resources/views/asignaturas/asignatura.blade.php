<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $asignatura->nombre }} - IESConnect</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&display=swap"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
<header>
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container-xl d-flex align-items-center">
            <!-- Menú hamburguesa -->
            <button class="navbar-toggler d-lg-none border-0 shadow-none me-2" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Logo -->
            <a class="navbar-brand d-none d-lg-block" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/logo.png') }}" alt="IES Vega de Mijas" style="height: 80px;">
            </a>

            <!-- Menú normal -->
            <div class="collapse navbar-collapse d-none d-lg-flex" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('asignaturas.asignaturas') }}">Clases</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('calendario') }}">Calendario</a>
                    </li>
                </ul>
            </div>

            <!-- Parte derecha -->
            <div class="d-flex align-items-center border-start ms-auto">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                       data-bs-toggle="dropdown">
                        <img src="{{ asset('images/EjemploPerfilUsuario.png') }}" alt="Profile" class="rounded-circle"
                             style="height: 40px;">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile') }}">Perfil</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Menú lateral -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menú</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('asignaturas.asignaturas') }}">Clases</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('calendario') }}">Calendario</a>
                </li>
            </ul>
        </div>
    </div>
</header>

<main class="container-xl my-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>{{ $asignatura->nombre }}</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Tareas Pendientes</h5>
                </div>
                <div class="card-body">
                    @if($asignatura->tareas->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($asignatura->tareas as $tarea)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="{{ route('asignaturas.tarea', ['id' => $tarea->id]) }}" class="text-decoration-none">
                                            <h6 class="mb-1">{{ $tarea->nombre }}</h6>
                                        </a>
                                        <small class="text-muted">Fecha límite: {{ $tarea->created_at->format('d/m/Y') }}</small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">Pendiente</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">No hay tareas pendientes.</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recursos</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">No hay recursos disponibles.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Información</h5>
                </div>
                <div class="card-body">
                    <p><strong>Profesor:</strong> Por determinar</p>
                    <p><strong>Horario:</strong> Por determinar</p>
                    <p><strong>Aula:</strong> Por determinar</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Próximos Eventos</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">No hay eventos próximos.</p>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
</body>

</html>
