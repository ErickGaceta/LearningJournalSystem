<?php

use Illuminate\Support\Facades\Route;
use App\Models\Document;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/documents/{document}', function (Document $document) {
    return view('documents.show', compact('document'));
})->name('documents.show');

Route::get('/documents/create', function () {
    return view('documents.create');
})->name('documents.create');

require __DIR__ . '/settings.php';
