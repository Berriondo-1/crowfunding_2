<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\ProfileController;


Route::get('/', function () {
    return view('welcome');
});

// Panel de ADMIN
Route::get('/admin', AdminDashboard::class)
    ->middleware(['auth', 'role:ADMIN'])
    ->name('admin.dashboard');

// Panel de AUDITOR
Route::get('/auditor', function () {
    return view('auditor.dashboard');
})->middleware(['auth', 'role:AUDITOR'])->name('auditor.dashboard');

// Panel de CREADOR
Route::get('/creator', function () {
    return view('creator.dashboard');
})->middleware(['auth', 'role:CREADOR'])->name('creator.dashboard');

// Panel de COLABORADOR
Route::get('/colaborador', function () {
    return view('colaborador.dashboard');
})->middleware(['auth', 'role:COLABORADOR'])->name('colaborador.dashboard');

// Dashboard general (fallback)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
