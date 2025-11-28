<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskDocumentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard',[DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class); 
   Route::post('/users/{user}/make-manager', [UserController::class, 'makeManager'])
    ->name('users.makeManager');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('tasks', TaskController::class);

    Route::post('tasks/{task}/documents', [TaskDocumentController::class, 'store'])->name('tasks.documents.store');
    Route::get('documents/{document}/download', [TaskDocumentController::class, 'download'])->name('documents.download');
    Route::delete('documents/{document}', [TaskDocumentController::class, 'destroy'])->name('documents.destroy');

});

require __DIR__.'/auth.php';
