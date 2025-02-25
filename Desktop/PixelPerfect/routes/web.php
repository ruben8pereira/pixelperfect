<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportCommentController;
use App\Http\Controllers\ReportImageController;
use App\Http\Controllers\InvitationController;

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


// Guest routes for invitations
Route::get('/invitations/{token}', [InvitationController::class, 'accept'])
    ->name('invitations.accept');
Route::post('/invitations/{token}/process', [InvitationController::class, 'process'])
    ->name('invitations.process');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Reports
    Route::resource('reports', ReportController::class);
    Route::get('/reports/{report}/export-pdf', [ReportController::class, 'exportPdf'])
        ->name('reports.export-pdf');

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

    // Invitations
    Route::get('/invitations', [InvitationController::class, 'index'])
        ->name('invitations.index');
    Route::post('/invitations', [InvitationController::class, 'store'])
        ->name('invitations.store');
    Route::post('/invitations/{invitation}/resend', [InvitationController::class, 'resend'])
        ->name('invitations.resend');
    Route::delete('/invitations/{invitation}', [InvitationController::class, 'cancel'])
        ->name('invitations.cancel');
});

require __DIR__.'/auth.php';
