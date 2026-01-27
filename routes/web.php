<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('create-journal', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Document routes (Training Reports)
Route::middleware(['auth', 'verified'])->group(function () {
    // List all documents
    Route::get('documents', [DocumentController::class, 'index'])->name('documents.index');
    
    // Create new document
    Route::get('documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('documents', [DocumentController::class, 'store'])->name('documents.store');
    
    // View single document (viewer)
    Route::get('documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    
    // Edit document (editor)
    Route::get('documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
    Route::put('documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    
    // Delete document
    Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
});

require __DIR__.'/settings.php';