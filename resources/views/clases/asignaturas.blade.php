<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Asignaturas</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&display=swap"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!--  Bootstrap Icons -->
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
            <a class="navbar-brand d-none d-lg-block" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="IES Vega de Mijas" style="height: 80px;">
            </a>

            <!-- Menú normal -->
            <div class="collapse navbar-collapse d-none d-lg-flex" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('clases.asignaturas') }}">Clases</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('calendario') }}">Calendario</a>
                    </li>
                </ul>
            </div>

            <!-- Parte derecha -->
            <div class="d-flex align-items-center border-start ms-auto">
                <button class="btn me-3 border-0" data-bs-toggle="modal" data-bs-target="#anadirAsignatura">
                    <i class="bi bi-journal-plus"></i>
                </button>
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

    <!-- Modal Añadir Asignatura -->

    <div class="modal fade" id="anadirAsignatura" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalLabel">Unirse a Asignatura</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('clases.unirse') }}">
                    @csrf
                    <div class="modal-body">
                        <label for="codigoClase" class="form-label">Código de Asignatura</label>
                        <input type="text" id="codigoClase" name="codigo_clase" class="form-control" placeholder="Código de Asignatura">

                        <p class="mt-3 text-muted">
                            Para iniciar sesión con un código de Asignatura:<br>
                            • Usa una cuenta autorizada<br>
                            • Usa un código de clase con números, sin espacios ni símbolos
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn border-black" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn border-black">Unirme</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Menú lateral -->
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
</header>

<main class="d-flex justify-content-center mt-4">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4 container-xxl">
        <div class="col">
            <a href="{{ route('clases.biologia') }}" class="text-decoration-none">
                <div class="card">
                    <img src="{{ asset('images/biologia.jpg') }}" class="card-img-top w-100 object-fit-cover" alt="..."
                         style="height: 180px;">
                    <div class="card-body">
                        <h5 class="card-title text-black">Biología</h5>
                        <p class="card-text m-0 text-black">Tareas Pendientes:</p>
                        <ul class="list-unstyled text-info">
                            <a href="{{ route('clases.tarea', ['id' => 1]) }}" class="text-decoration-none">
                                <li>Presentación ciclo celular</li>
                            </a>
                        </ul>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('clases.matematicas') }}" class="text-decoration-none">
                <div class="card">
                    <img src="{{ asset('images/matematicas.jpg') }}" class="card-img-top w-100 object-fit-cover" alt="..."
                         style="height: 180px;">
                    <div class="card-body">
                        <h5 class="card-title">Matemáticas</h5>
                        <p class="card-text m-0">Tareas Pendientes:</p>
                        <ul class="list-unstyled text-info">
                        </ul>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('clases.historia') }}" class="text-decoration-none">
                <div class="card">
                    <img src="{{ asset('images/historia.jpg') }}" class="card-img-top w-100 object-fit-cover" alt="..."
                         style="height: 180px;">
                    <div class="card-body">
                        <h5 class="card-title">Historia</h5>
                        <p class="card-text m-0">Tareas Pendientes:</p>
                        <ul class="list-unstyled text-info">
                        </ul>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('clases.educacion-fisica') }}" class="text-decoration-none">
                <div class="card">
                    <img src="{{ asset('images/educacionFisica.jpg') }}" class="card-img-top w-100 object-fit-cover" alt="..."
                         style="height: 180px;">
                    <div class="card-body">
                        <h5 class="card-title">Educación Física</h5>
                        <p class="card-text m-0">Tareas Pendientes:</p>
                        <ul class="list-unstyled text-info">
                        </ul>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('clases.tecnologia') }}" class="text-decoration-none">
                <div class="card">
                    <img src="{{ asset('images/herramientas.jpg') }}" class="card-img-top w-100 object-fit-cover" alt="..."
                         style="height: 180px;">
                    <div class="card-body">
                        <h5 class="card-title">Tecnología</h5>
                        <p class="card-text m-0">Tareas Pendientes:</p>
                        <ul class="list-unstyled text-info">
                        </ul>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('clases.informatica') }}" class="text-decoration-none">
                <div class="card">
                    <img src="{{ asset('images/informatica.jpg') }}" class="card-img-top w-100 object-fit-cover" alt="..."
                         style="height: 180px;">
                    <div class="card-body">
                        <h5 class="card-title">Informática</h5>
                        <p class="card-text m-0">Tareas Pendientes:</p>
                        <ul class="list-unstyled text-info">
                        </ul>
                    </div>
                </div>
            </a>
        </div>
    </div>
</main>

<script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
</body>

</html>

