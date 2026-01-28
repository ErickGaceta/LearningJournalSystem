<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentPrintController;use App\Http\Controllers\LoginController;

Route::post('/login', [LoginController::class, 'store'])->name('login.store');


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
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

    // Print preview route
    Route::get(
        '/documents/{document}/print-preview',
        [DocumentPrintController::class, 'preview']
    )->name('documents.print.preview');
});

require __DIR__ . '/settings.php';
