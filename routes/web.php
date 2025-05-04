<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\ProgresoTareaController;
use App\Http\Controllers\ArchivoTareaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CalendarioController;
/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/cookies', 'cookies')->name('cookies');
Route::get('/contacto', [ContactController::class, 'show'])->name('pages.contact');
Route::post('/contacto', [ContactController::class, 'submit'])->name('contact.submit');

/*
|--------------------------------------------------------------------------
| Rutas exclusivas para profesores
|--------------------------------------------------------------------------
*/

Route::get('/tareas/create', [TareaController::class, 'create'])->name('tareas.create')->middleware(\App\Http\Middleware\EsProfesor::class);
Route::post('/tareas', [TareaController::class, 'store'])->name('tareas.store')->middleware(\App\Http\Middleware\EsProfesor::class);
Route::get('/tareas/{tarea}/edit', [TareaController::class, 'edit'])->name('tareas.edit')->middleware(\App\Http\Middleware\EsProfesor::class);
Route::put('/tareas/{tarea}', [TareaController::class, 'update'])->name('tareas.update')->middleware(\App\Http\Middleware\EsProfesor::class);
Route::delete('/tareas/{tarea}', [TareaController::class, 'destroy'])->name('tareas.destroy')->middleware(\App\Http\Middleware\EsProfesor::class);

// Asignar niveles
Route::get('/tareas/{tarea}/asignar-nivel', [ProgresoTareaController::class, 'create'])->name('progreso.create')->middleware(\App\Http\Middleware\EsProfesor::class);
Route::post('/tareas/{tarea}/asignar-nivel', [ProgresoTareaController::class, 'store'])->name('progreso.store')->middleware(\App\Http\Middleware\EsProfesor::class);

// Feedback
Route::put('/entregas/{entrega}/feedback', [EntregaController::class, 'updateFeedback'])->name('entregas.feedback')->middleware(\App\Http\Middleware\EsProfesor::class);

// Archivos
Route::delete('/archivos/{archivo}', [ArchivoTareaController::class, 'destroy'])->name('archivos.destroy')->middleware(\App\Http\Middleware\EsProfesor::class);

// Personalización de clases
Route::post('/asignaturas/{asignatura}/regenerar-codigo', [AsignaturaController::class, 'regenerarCodigo'])->name('asignaturas.regenerar-codigo')->middleware(\App\Http\Middleware\EsProfesor::class);
Route::put('/asignaturas/{asignatura}/personalizar', [AsignaturaController::class, 'personalizar'])->name('asignaturas.personalizar')->middleware(\App\Http\Middleware\EsProfesor::class);

/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

/*
|--------------------------------------------------------------------------
| Rutas autenticadas (generales)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/profile', 'profile')->name('profile');

    // Asignaturas
    Route::get('/asignaturas', [AsignaturaController::class, 'index'])->name('asignaturas.index');
    Route::get('/asignaturas/{slug}', [AsignaturaController::class, 'show'])->name('asignaturas.show');
    Route::post('/asignaturas/unirse', [AsignaturaController::class, 'unirse'])->name('asignaturas.unirse');

    // Publicaciones asignaturas
    Route::post('/asignaturas/{asignatura}/publicaciones', [\App\Http\Controllers\PublicacionController::class, 'store'])->name('publicaciones.store');
    Route::delete('/publicaciones/{publicacion}', [\App\Http\Controllers\PublicacionController::class, 'destroy'])->name('publicaciones.destroy');
    Route::put('/publicaciones/{publicacion}', [\App\Http\Controllers\PublicacionController::class, 'update'])->name('publicaciones.update');

    // Tareas visibles para todos (index y show)
    Route::resource('tareas', TareaController::class)->only(['index', 'show']);
    Route::get('/tareas/{tarea}/ver', [TareaController::class, 'showEstudiante'])->name('tareas.ver.estudiante');

    // Entrega por estudiante
    Route::post('progreso/{progreso}/entregar', [EntregaController::class, 'store'])->name('entregas.store');

    // Calendario
    Route::view('/calendario', 'calendario.index')->name('calendario');
    Route::get('/calendario/eventos', [CalendarioController::class, 'eventos'])->name('calendario.eventos');

});


/*
|--------------------------------------------------------------------------
| Recursos
|--------------------------------------------------------------------------
*/
Route::resource('usuarios', UsuarioController::class);
