<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HRController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentPrintController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ChangePasswordController;

// ========== Guest Routes (No Auth Required) ==========
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ========== Login Route ==========
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

// ========== Change Password Routes (Auth Required) ==========
Route::middleware('auth')->group(function () {
    Route::get('/change-password', [ChangePasswordController::class, 'show'])->name('password.change.show');
    Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('password.change.update');
});

// ========== Dashboard Redirect Route ==========
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();

    return match($user->user_type) {
        'admin' => redirect()->route('admin.dashboard'),
        'hr' => redirect()->route('hr.dashboard'),
        'user' => redirect()->route('user.dashboard'),
        default => redirect()->route('user.dashboard'),
    };
})->middleware('auth')->name('dashboard');

// ========== Logout Route ==========
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// ========== Admin Routes ==========
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // User Management
    Route::get('/users', [AdminController::class, 'usersIndex'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/show', [AdminController::class, 'showUser'])->name('users.show');
    Route::post('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    // Position Management
    Route::get('/positions', [AdminController::class, 'positionsIndex'])->name('positions.index');
    Route::get('/positions/create', [AdminController::class, 'createPosition'])->name('positions.create');
    Route::post('/positions', [AdminController::class, 'storePosition'])->name('positions.store');
    Route::get('/positions/{position}', [AdminController::class, 'showPosition'])->name('positions.show');
    Route::get('/positions/{position}/edit', [AdminController::class, 'editPosition'])->name('positions.edit');
    Route::put('/positions/{position}', [AdminController::class, 'updatePosition'])->name('positions.update');
    Route::delete('/positions/{position}', [AdminController::class, 'destroyPosition'])->name('positions.destroy');

    // Division Management
    Route::get('/divisions', [AdminController::class, 'divisionsIndex'])->name('divisions.index');
    Route::get('/divisions/create', [AdminController::class, 'createDivision'])->name('divisions.create');
    Route::post('/divisions', [AdminController::class, 'storeDivision'])->name('divisions.store');
    Route::get('/divisions/{division}', [AdminController::class, 'showDivision'])->name('divisions.show');
    Route::get('/divisions/{division}/edit', [AdminController::class, 'editDivision'])->name('divisions.edit');
    Route::put('/divisions/{division}', [AdminController::class, 'updateDivision'])->name('divisions.update');
    Route::delete('/divisions/{division}', [AdminController::class, 'destroyDivision'])->name('divisions.destroy');
});

// ========== HR Routes ==========
Route::middleware(['auth'])->prefix('hr')->name('hr.')->group(function () {
    Route::get('/dashboard', [HRController::class, 'dashboard'])->name('dashboard');

    // Training Module Management
    Route::get('/modules', [HRController::class, 'modulesIndex'])->name('modules.index');
    Route::get('/modules/create', [HRController::class, 'createModule'])->name('modules.create');
    Route::post('/modules', [HRController::class, 'storeModule'])->name('modules.store');
    Route::get('/modules/{module}/edit', [HRController::class, 'editModule'])->name('modules.edit');
    Route::put('/modules/{module}', [HRController::class, 'updateModule'])->name('modules.update');
    Route::delete('/modules/{module}', [HRController::class, 'destroyModule'])->name('modules.destroy');

    // Assignment Management
    Route::get('/assignments', [HRController::class, 'assignmentsIndex'])->name('assignments.index');
    Route::get('/assignments/create', [HRController::class, 'createAssignment'])->name('assignments.create');
    Route::post('/assignments', [HRController::class, 'storeAssignment'])->name('assignments.store');
    Route::delete('/assignments/{assignment}', [HRController::class, 'destroyAssignment'])->name('assignments.destroy');
});

// ========== User Routes ==========
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    // Training Tracking
    Route::get('/trainings', [UserController::class, 'myTrainings'])->name('trainings.index');
    Route::get('/trainings/{assignment}', [UserController::class, 'showTraining'])->name('trainings.show');

    // Document Management
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create/{assignment}', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents/{assignment}', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Document Print
    Route::get('/documents/{document}/preview', [DocumentPrintController::class, 'previewPdf'])->name('documents.preview');
});

require __DIR__ . '/settings.php';
