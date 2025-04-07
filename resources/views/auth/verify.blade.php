@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verifica tu dirección de correo electronico') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Un enlace de verificación se ha enviado a su email') }}
                        </div>
                    @endif

                    {{ __('Antes de seguir, revise su correo electronico') }}
                    {{ __('Si no ha recibido el correo electronico') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('Pulse aquí para enviar otro correo') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
