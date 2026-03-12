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
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\CertificateController;

// ========== Guest Routes (No Auth Required) ==========
Route::get('/', fn() => redirect()->route('login'))->name('home');

// ========== Guest Routes ==========
Route::middleware('guest')->group(function () {
    Route::get('/login',  [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

// ========== Auth Routes ==========
Route::middleware('auth')->group(function () {

    // Change Password
    Route::get('/change-password',  [ChangePasswordController::class, 'show'])->name('password.change.show');
    Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('password.change.update');

    // Dashboard redirect — move match() to LoginController::redirectByUserType()
    Route::get('/dashboard', function () {
        return match (Auth::user()->user_type) {
            'admin' => redirect()->route('admin.dashboard'),
            'hr'    => redirect()->route('hr.dashboard'),
            default => redirect()->route('user.dashboard'),
        };
    })->name('dashboard');

    // Logout
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

// ========== Admin Routes ==========
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // User Management
    Route::get('/users', [AdminController::class, 'usersIndex'])->name('users.index');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::patch('/users/{user}/archive', [AdminController::class, 'archiveUser'])->name('users.archive');
    Route::patch('/users/{user}/restore', [AdminController::class, 'restoreUser'])->name('users.restore');
    Route::post('/users/{user}/reset-password', [AdminController::class, 'resetPassword'])->name('users.reset-password');

    // Position Management
    Route::get('/positions', [AdminController::class, 'positionsIndex'])->name('positions.index');
    Route::post('/positions', [AdminController::class, 'storePosition'])->name('positions.store');
    Route::put('/positions/{position}', [AdminController::class, 'updatePosition'])->name('positions.update');
    Route::delete('/positions/{position}', [AdminController::class, 'destroyPosition'])->name('positions.destroy');

    // Division Management
    Route::get('/divisions', [AdminController::class, 'divisionsIndex'])->name('divisions.index');
    Route::post('/divisions', [AdminController::class, 'storeDivision'])->name('divisions.store');
    Route::put('/divisions/{division}', [AdminController::class, 'updateDivision'])->name('divisions.update');
    Route::delete('/divisions/{division}', [AdminController::class, 'destroyDivision'])->name('divisions.destroy');
});

// ========== HR Routes ==========
Route::middleware(['auth'])->prefix('hr')->name('hr.')->group(function () {
    Route::get('/dashboard', [HRController::class, 'dashboard'])->name('dashboard');

   // Training Module Management
    Route::get('/modules', [HRController::class, 'modulesIndex'])->name('modules.index');
    Route::get('/modules/archived', [HRController::class, 'modulesArchived'])->name('modules.archived'); // ← renamed to avoid conflict
    Route::patch('/modules/{module}/archive', [HRController::class, 'archiveModule'])->name('modules.archive_action');
    Route::post('/modules', [HRController::class, 'storeModule'])->name('modules.store');
    Route::put('/modules/{module}', [HRController::class, 'updateModule'])->name('modules.update');
    Route::delete('/modules/{module}', [HRController::class, 'destroyModule'])->name('modules.destroy');
    Route::patch('/modules/{module}/restore', [HRController::class, 'restoreModule'])->name('modules.restore');

    // Assignment Management
    Route::post('/assignments', [HRController::class, 'storeAssignment'])->name('assignments.store');
    Route::delete('/assignments/{assignment}', [HRController::class, 'destroyAssignment'])->name('assignments.destroy');

    // Monitoring Routes
    Route::get('/monitoring', [HRController::class, 'monitoringIndex'])->name('monitoring.index');
    Route::get('/monitoring/documents/{document}/preview', [HRController::class, 'previewDocument'])->name('monitoring.document.preview');
    Route::post('/documents/{document}/archive', [HRController::class, 'archiveDocument'])->name('documents.archive');
    Route::get('/documents/{document}/preview', [HRController::class, 'previewDocument'])->name('documents.preview');
    Route::prefix('monitoring/certificates')->name('monitoring.certificates.')->group(function () {

        // Preview (loaded in iframe)
        Route::get('{training}/{employee}', [CertificateController::class, 'preview'])->name('preview');

        // PDF download
        Route::get('{training}/{employee}/download', [CertificateController::class, 'download'])->name('download');
    });
});

// ========== User Routes ==========
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    // Training Tracking
    Route::get('/trainings', [UserController::class, 'myTrainings'])->name('trainings.index');

    // User Profile Routes
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Document Management
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create/{assignment}', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents/{assignment}', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::patch('/documents/{document}/archive', [DocumentController::class, 'archive'])->name('documents.archive');

    Route::post('/signature', [UserController::class, 'uploadSignature'])->name('signature.save');

    // Document Print
    Route::get('/documents/{document}/preview', [DocumentPrintController::class, 'previewPdf'])->name('documents.preview');
    Route::patch('/documents/{document}/restore', [DocumentController::class, 'restore'])->name('documents.restore');
});

require __DIR__ . '/settings.php';
