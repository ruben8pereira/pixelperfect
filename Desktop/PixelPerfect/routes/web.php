

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportCommentController;
use App\Http\Controllers\ReportImageController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportDefectController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Default welcome route
/*Route::get('/', function () {
    return view('layouts.navigation');
});*/

Route::get('/', function () {
    return view('home');
});


Route::get('/home', function () {
    return view('home');
});

// Redirect Filament admin/reports to your custom reports page
Route::redirect('/admin/reports/reports', '/reports', 301);
Route::redirect('/admin/reports/create', '/reports/create', 301);
Route::get('/admin/reports/{id}/edit', function ($id) {
    return redirect("/reports/{$id}/edit", 301);
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Reports
    Route::resource('reports', ReportController::class);
    Route::get('/reports/{report}/export-pdf', [ReportController::class, 'exportPdf'])
        ->name('reports.export-pdf');
    Route::get('/reports/{report}/preview-pdf', [ReportController::class, 'previewPdf'])
        ->name('reports.preview-pdf');
    Route::get('reports/create', [ReportController::class, 'create'])
        ->name('reports.create');

    // Report Comments
    Route::post('/reports/{report}/comments', [ReportCommentController::class, 'store'])
        ->name('reports.comments.store');
    Route::put('/reports/{report}/comments/{comment}', [ReportCommentController::class, 'update'])
        ->name('reports.comments.update');
    Route::delete('/reports/{report}/comments/{comment}', [ReportCommentController::class, 'destroy'])
        ->name('reports.comments.destroy');

    // Report Images
    Route::post('/reports/{report}/images', [ReportImageController::class, 'store'])
        ->name('reports.images.store');
    Route::put('/reports/{report}/images/{image}', [ReportImageController::class, 'update'])
        ->name('reports.images.update');
    Route::delete('/reports/{report}/images/{image}', [ReportImageController::class, 'destroy'])
        ->name('reports.images.destroy');


    // Report defects
    Route::post('/reports/{report}/defects', [ReportDefectController::class, 'store'])
        ->name('reports.defects.store');
    Route::put('/reports/{report}/defects/{defect}', [ReportDefectController::class, 'update'])
        ->name('reports.defects.update');
    Route::delete('/reports/{report}/defects/{defect}', [ReportDefectController::class, 'destroy'])
        ->name('reports.defects.destroy');

    // Report sharing routes
    Route::post('/reports/{report}/share', [App\Http\Controllers\ReportController::class, 'share'])
        ->name('reports.share');
    Route::get('/reports/{report}/shares', [App\Http\Controllers\ReportController::class, 'showInvitations'])
        ->name('reports.shares');
    Route::delete('/reports/shares/{reportInvitation}', [App\Http\Controllers\ReportController::class, 'cancelInvitation'])
        ->name('reports.shares.cancel');

    // Report sharing routes
    Route::get('/reports/{report}/invitations', [ReportController::class, 'showInvitations'])
        ->name('reports.invitations');
    Route::get('/reports/shared/{token}', [ReportController::class, 'showShared'])
        ->name('reports.shared')
        ->withoutMiddleware('auth'); // This allows unauthenticated access to shared reports

    // User Management Routes (Admin only)
    Route::middleware(['auth', 'can:access-admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('/users/{user}/archive', [UserController::class, 'archive'])->name('users.archive');
        Route::post('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
    });

    Route::get('/language/{locale}', [App\Http\Controllers\LanguageController::class, 'switch'])
        ->name('language.switch');
});

require __DIR__ . '/auth.php';
