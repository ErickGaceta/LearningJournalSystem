<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentPrintController;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TrainingModuleController;
use App\Http\Controllers\PositionsController;
use App\Http\Controllers\DivisionsController;

Route::post('/login', [LoginController::class, 'store'])->name('login.store');

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', function () {
    $user = Auth::user();

    return match($user->user_type) {
        'admin' => view('pages.admin.index'),
        'user' => view('pages.users.index'),
        default => abort(403, 'Unauthorized'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// Document routes (Training Reports)
Route::middleware(['auth', 'verified'])->group(function () {
    // Documents
    Route::resource('documents', DocumentController::class);
    Route::get('/documents/{document}/print-preview', [DocumentController::class, 'printPreview'])->name('documents.print.preview');
    Route::get('/documents/{document}/export-word', [DocumentPrintController::class, 'exportWord'])->name('documents.export.word');

    Route::resource('modules', TrainingModuleController::class);

    // Positions - use resource route
    Route::resource('positions', PositionsController::class);
    
    // Divisions - use resource route
    Route::resource('divisions', DivisionsController::class);
});

require __DIR__ . '/settings.php';