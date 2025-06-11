@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Regístrate') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="nombre" class="col-md-4 col-form-label text-md-end">{{ __('Nombre') }}</label>
                                <div class="col-md-6">
                                    <input  id="nombre" type="text"
                                            class="form-control @error('nombre') is-invalid @enderror"
                                            name="nombre" value="{{ old('nombre') }}"
                                            required minlength="1" maxlength="20"
                                            title="Introduce entre 1 y 20 letras"
                                            autocomplete="given-name" autofocus>
                                    @error('nombre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="apellidos" class="col-md-4 col-form-label text-md-end">{{ __('Apellidos') }}</label>
                                <div class="col-md-6">
                                    <input  id="apellidos" type="text"
                                            class="form-control @error('apellidos') is-invalid @enderror"
                                            name="apellidos" value="{{ old('apellidos') }}"
                                            required minlength="3" maxlength="50"
                                            title="Introduce entre 3 y 50 letras"
                                            autocomplete="family-name">
                                    @error('apellidos')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Correo electrónico') }}</label>
                                <div class="col-md-6">
                                    <input  id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email') }}"
                                            required
                                            pattern="^[A-Za-z0-9._%+-]{3,}@g\.educaand\.es$"
                                            title="Mínimo 3 caracteres antes de @ y debe terminar en @g.educaand.es"
                                            autocomplete="email">
                                    <small class="form-text text-muted">
                                        Debe terminar en <code>@g.educaand.es</code> y tener al menos 3 caracteres antes de&nbsp;@.
                                    </small>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Contraseña') }}</label>
                                <div class="col-md-6">
                                    <input  id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            name="password" required minlength="8"
                                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$"
                                            title="Mínimo 8 caracteres, con mayúscula, minúscula, número y símbolo"
                                            autocomplete="new-password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirmar contraseña') }}</label>
                                <div class="col-md-6">
                                    <input  id="password-confirm" type="password"
                                            class="form-control"
                                            name="password_confirmation"
                                            required autocomplete="new-password"
                                            title="Debe coincidir con la contraseña">
                                    <small class="form-text text-muted">
                                        Debe coincidir con la contraseña anterior.
                                    </small>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Regístrate') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('form[action="{{ route('register') }}"]');

            form.addEventListener('submit', e => {
                form.querySelectorAll('.js-error').forEach(el => el.remove());

                const addError = (field, msg) => {
                    const el = document.createElement('small');
                    el.className = 'text-danger js-error';
                    el.textContent = msg;
                    form[field].parentNode.appendChild(el);
                };

                let valid = true;

                const emailOK = /^[A-Za-z0-9._%+-]{3,}@g\.educaand\.es$/.test(form.email.value);
                if (!emailOK) {
                    addError('email',
                        'Ejemplo válido: usuario@g.educaand.es (mín. 3 car. antes de @).');
                    valid = false;
                }

                const passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/;
                if (!passRegex.test(form.password.value)) {
                    addError('password',
                        'La contraseña debe tener ≥8 caracteres, con mayúscula, minúscula, número y símbolo; p. ej.: Abc123$%.');
                    valid = false;
                } else if (form.password.value !== form.password_confirmation.value) {
                    addError('password_confirmation',
                        'Las contraseñas no coinciden. Escríbelas idénticas en ambos campos.');
                    valid = false;
                }

                if (!valid) e.preventDefault();
            });
        });
    </script>
@endsection
