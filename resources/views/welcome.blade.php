<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IESConnect - Pagina Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"> <!--  Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .floating-img {
            margin-top: -100px;
            position: relative;
            z-index: 1;
        }
        /* Aplicar la fuente */
        body {
            font-family: 'Cabin', sans-serif;
        }
    </style>
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container-xl d-flex align-items-center">
            <!-- Menú hamburguesa -->
            <button class="navbar-toggler d-lg-none border-0 shadow-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
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
                        <a class="nav-link" aria-current="page" href="{{ route('asignaturas.index') }}">Clases</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('calendario') }}">Calendario</a>
                    </li>
                </ul>
            </div>

            <!-- Parte derecha -->
            <div class="d-flex align-items-center border-start ms-auto">
                <button class="btn me-3 border-0">
                    <i class="bi bi-bell"></i>
                </button>
                <div class="dropdown">
                    @auth
                        <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->nombre_completo }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
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
                            <button class="btn btn-primary">
                                Iniciar Sesión
                            </button>
                        </a>
                    @endauth
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
                    <a class="nav-link" href="{{ route('asignaturas.index') }}">Clases</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('calendario') }}">Calendario</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Cabecera -->
    <div class="container-xl mt-5">
        <div class="row align-items-center mb-5">
            <div class="col-md-6 text-center text-md-start">
                <div class="mb-3">
                    <img src="{{ asset('images/LogoConjuntoVMA_A_E.jpg') }}" alt="" class="img-fluid">
                </div>
                <h1 class="fw-bold">IESConnect facilita la enseñanza</h1>
                <p class="text-muted">Las herramientas de IESConnect funcionan conjuntamente para transformar la enseñanza y el aprendizaje, de forma que cada profesor y alumno pueda desarrollar todo su potencial.</p>
                <a href="{{ route('register') }}" class="btn btn-primary">Empieza a usar IESConnect</a>
            </div>
            <div class="col-md-6 position-relative mt-3">
                <div class="position-relative">
                    <img src="{{ asset('images/estudioCasa.jpg') }}" alt="Video conferencia" class="img-fluid w-100 object-fit-cover rounded-top-5" style="height: 300px;">
                    <img src="{{ asset('images/estudiantes.jpg') }}" alt="Estudiantes" class="floating-img w-100 object-fit-cover rounded-bottom-5" style="height: 300px;">
                </div>
            </div>
        </div>
    </div>
</header>

<main>
    <!-- Características -->
    <section class="container-xl text-center my-5">
        <h2 class="fw-bold">¿Por qué elegir IESConnect?</h2>
        <div class="row mt-4">
            <div class="col-md-4">
                <i class="bi bi-laptop display-4 text-primary"></i>
                <h5 class="mt-3">Acceso 24/7</h5>
                <p>Conéctate a tus clases desde cualquier lugar y en cualquier momento.</p>
            </div>
            <div class="col-md-4">
                <i class="bi bi-person-check display-4 text-primary"></i>
                <h5 class="mt-3">Interacción en tiempo real</h5>
                <p>Comunícate con tus profesores y compañeros sin barreras.</p>
            </div>
            <div class="col-md-4">
                <i class="bi bi-shield-check display-4 text-primary"></i>
                <h5 class="mt-3">Seguridad garantizada</h5>
                <p>Protegemos tus datos y garantizamos una experiencia segura.</p>
            </div>
        </div>
    </section>

    <!-- Carrousel de imagenes -->
    <section class="container-xl text-center my-5">
        <div id="carouselExampleIndicators" class="carousel slide">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner rounded-3">
                <div class="carousel-item active">
                    <img src="{{ asset('images/Centro.jpg') }}" class="d-block w-100 object-fit-cover" alt="Imagen Del centro" style="height: 500px;">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/Graduacion.jpg') }}" class="d-block w-100 object-fit-cover" alt="Imagen de alumnos" style="height: 500px;">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/Ciencias.jpg') }}" class="d-block w-100 object-fit-cover" alt="Imagen de lo que sea" style="height: 500px;">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <!-- Testimonios -->
    <section class="bg-light py-5">
        <div class="container-xl text-center">
            <h2 class="fw-bold">Lo que dicen nuestros usuarios</h2>
            <div class="row mt-4">
                <div class="col-md-4">
                    <p>"IESConnect ha cambiado la forma en que aprendemos. Es intuitivo y fácil de usar."</p>
                    <h6>- Juan Pérez</h6>
                </div>
                <div class="col-md-4">
                    <p>"La plataforma nos ha permitido organizar mejor nuestras clases y materiales."</p>
                    <h6>- Ana Gómez</h6>
                </div>
                <div class="col-md-4">
                    <p>"Recomiendo IESConnect a todos los centros educativos. Es increíble."</p>
                    <h6>- Luis Fernández</h6>
                </div>
            </div>
        </div>
    </section>
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
                    <li><a href="{{ route('asignaturas.index') }}" class="text-white text-decoration-none small">Clases</a></li>
                    <li><a href="{{ route('contact') }}" class="text-white text-decoration-none small">Contacto</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Síguenos</h5>
                <a href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
                <a href="https://x.com/iesvegademijas" class="text-white me-2"><i class="bi bi-twitter"></i></a>
                <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
            </div>
        </div>
    </div>
    <div class="bg-dark text-center">
        <p class="mt-3 small">&copy; {{ date('Y') }} IESConnect. Todos los derechos reservados.</p>
    </div>
</footer>

<script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
</body>
</html>

