<?php
// web.php
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
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

    Route::get('/clases/asignaturas', [UserController::class, 'index'])->name('clases.asignaturas');

    Route::get('/clases/asignatura/{slug}', function ($slug) {
        $asignatura = App\Models\Asignatura::where('slug', $slug)->firstOrFail();
        return view('clases.asignatura', compact('asignatura'));
    })->name('clases.asignatura');

    Route::post('/clases/unirse', function () {
        return redirect()->route('clases.asignaturas');
    })->name('clases.unirse');

    Route::get('/clases/tarea/{id}', function ($id) {
        $tarea = App\Models\Tarea::findOrFail($id);
        return view('clases.tarea', compact('tarea'));
    })->name('clases.tarea');

    Route::view('/calendario', 'calendario.index')->name('calendario');

    // Clases específicas
    Route::view('/clases/biologia', 'clases.biologia')->name('clases.biologia');
    Route::view('/clases/matematicas', 'clases.matematicas')->name('clases.matematicas');
    Route::view('/clases/historia', 'clases.historia')->name('clases.historia');
    Route::view('/clases/educacion-fisica', 'clases.educacion-fisica')->name('clases.educacion-fisica');
    Route::view('/clases/tecnologia', 'clases.tecnologia')->name('clases.tecnologia');
    Route::view('/clases/informatica', 'clases.informatica')->name('clases.informatica');
});
