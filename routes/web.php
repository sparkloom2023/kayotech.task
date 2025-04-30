<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\TaskController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// Route::get('/Task', [TaskController::class, 'index'])->name('tasks.index');
// Route::post('/Task', [TaskController::class, 'store'])->name('tasks.store');
// Route::put('/Task/{task}', [TaskController::class, 'update'])->name('tasks.update');
// route::delete('/Task/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
// Route::post('/subtasks', [TaskController::class, 'storeSubtask'])->name('subtasks.store');
Route::resource('tasks', TaskController::class);
    Route::resource('subtasks', SubtaskController::class)->only(['update', 'destroy']);
require __DIR__.'/auth.php';
