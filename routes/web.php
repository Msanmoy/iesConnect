<?php
// web.php
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\Profesor\TareaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// Rutas públicas
Route::view('/about', 'about')->name('about');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/cookies', 'cookies')->name('cookies');
Route::view('/contact', 'contact')->name('contact');

// Autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Recuperación de contraseña
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Rutas autenticadas
Route::middleware(['auth'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/profile', 'profile')->name('profile');

    Route::get('/asignaturas/asignaturas', [UserController::class, 'index'])->name('asignaturas.asignaturas');

    Route::get('/asignaturas/asignatura/{slug}', function ($slug) {
        $asignatura = App\Models\Asignatura::where('slug', $slug)->firstOrFail();
        return view('asignaturas.asignatura', compact('asignatura'));
    })->name('asignaturas.asignatura');

    Route::post('/asignaturas/unirse', function () {
        return redirect()->route('asignaturas.asignaturas');
    })->name('asignaturas.unirse');

    Route::get('/asignaturas/tarea/{id}', function ($id) {
        $tarea = App\Models\Tarea::findOrFail($id);
        return view('asignaturas.tarea', compact('tarea'));
    })->name('asignaturas.tarea');

    Route::view('/calendario', 'calendario.index')->name('calendario');
    Route::get('/calendario/eventos', [CalendarioController::class, 'eventos'])->name('calendario.eventos');



    // Clases específicas
    Route::view('/asignaturas/biologia', 'asignaturas.biologia')->name('asignaturas.biologia');
    Route::view('/asignaturas/matematicas', 'asignaturas.matematicas')->name('asignaturas.matematicas');
    Route::view('/asignaturas/historia', 'asignaturas.historia')->name('asignaturas.historia');
    Route::view('/asignaturas/educacion-fisica', 'asignaturas.educacion-fisica')->name('asignaturas.educacion-fisica');
    Route::view('/asignaturas/tecnologia', 'asignaturas.tecnologia')->name('asignaturas.tecnologia');
    Route::view('/asignaturas/informatica', 'asignaturas.informatica')->name('asignaturas.informatica');
});

// Rutas para que los profesores creen tareas
Route::middleware(['auth', 'is_profesor'])->prefix('profesor')->group(function () {
    Route::get('tareas/create', [TareaController::class, 'create'])->name('tareas.create');
    Route::post('tareas', [TareaController::class, 'store'])->name('tareas.store');
});

Route::get('profesor/tareas', [TareaController::class, 'index'])->name('tareas.index');




