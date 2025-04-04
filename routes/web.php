<?php

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

