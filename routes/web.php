<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    Route::resource('services', ServiceController::class);

    Route::get('/appointments/search', [AppointmentController::class, 'search'])
        ->name('appointments.search');

    Route::resource('appointments', AppointmentController::class);

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';