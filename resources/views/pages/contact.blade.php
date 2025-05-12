@extends('layouts.app')

@section('title', 'Contacto')

@section('content')
        <h1 class="text-center fw-bold">Contacto</h1>
        <p class="text-center text-muted">¿Tienes alguna pregunta? Envíanos un mensaje.</p>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Tu nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="tucorreo@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="mensaje" class="form-label">Mensaje</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="4" placeholder="Escribe tu mensaje aquí" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Enviar mensaje</button>
                </form>
            </div>
        </div>
@endsection
