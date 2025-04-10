@extends('layouts.app')

@section('content')

<main>

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
@endsection

