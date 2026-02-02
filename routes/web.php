<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentPrintController;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PositionsController;
use App\Http\Controllers\DivisionsController;

Route::post('/login', [LoginController::class, 'store'])->name('login.store');


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', function () {
    $user = Auth::user();

    return match($user->user_type) {
        'admin' => view('pages.admin'),
        'user' => view('pages.user'),
        default => abort(403, 'Unauthorized'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// Document routes (Training Reports)
Route::middleware(['auth', 'verified'])->group(function () {
    // List all documents
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('/documents/{document}/print-preview', [DocumentController::class, 'printPreview'])->name('documents.print.preview');
    Route::get('/documents/{document}/export-word', [DocumentPrintController::class, 'exportWord'])->name('documents.export.word');

    Route::get('/positions', [PositionsController::class, 'positions'])->name('positions');
    Route::get('/divisions', [DivisionsController::class, 'divisions'])->name('divisions');
});

require __DIR__ . '/settings.php';
