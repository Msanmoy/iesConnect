<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth routes (you'll need to implement these with Laravel Breeze or Jetstream)
Route::get('/login', function() {
    // This is a placeholder. You should use Laravel's auth system.
    return view('auth.login');
})->name('login');

Route::post('/logout', function() {
    // This is a placeholder. You should use Laravel's auth system.
    return redirect('/');
})->name('logout');

Route::get('/register', function() {
    // This is a placeholder. You should use Laravel's auth system.
    return view('auth.register');
})->name('register');

Route::get('/profile', function() {
    // This is a placeholder. You should implement a profile page.
    return view('profile');
})->name('profile')->middleware('auth');

// Application routes
Route::get('/clases/asignaturas', function() {
    // This is a placeholder. You should implement a controller for this.
    return view('clases.asignaturas');
})->name('clases.asignaturas');

Route::get('/calendario', function() {
    // This is a placeholder. You should implement a controller for this.
    return view('calendario.index');
})->name('calendario');

Route::get('/contact', function() {
    // This is a placeholder. You should implement a contact page.
    return view('contact');
})->name('contact');

// Rutas de autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas de registro
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Rutas de recuperación de contraseña
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Rutas de páginas estáticas
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/cookies', function () {
    return view('cookies');
})->name('cookies');

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth'])->group(function () {
    Route::get('/clases/asignaturas', function () {
        return view('clases.asignaturas');
    })->name('clases.asignaturas');

    Route::post('/clases/unirse', function () {
        // Lógica para unirse a una clase
        return redirect()->route('clases.asignaturas');
    })->name('clases.unirse');

    Route::get('/clases/biologia', function () {
        return view('clases.biologia');
    })->name('clases.biologia');

    Route::get('/clases/matematicas', function () {
        return view('clases.matematicas');
    })->name('clases.matematicas');

    Route::get('/clases/historia', function () {
        return view('clases.historia');
    })->name('clases.historia');

    Route::get('/clases/educacion-fisica', function () {
        return view('clases.educacion-fisica');
    })->name('clases.educacion-fisica');

    Route::get('/clases/tecnologia', function () {
        return view('clases.tecnologia');
    })->name('clases.tecnologia');

    Route::get('/clases/informatica', function () {
        return view('clases.informatica');
    })->name('clases.informatica');

    Route::get('/clases/tarea/{id}', function ($id) {
        return view('clases.tarea', ['id' => $id]);
    })->name('clases.tarea');
});
