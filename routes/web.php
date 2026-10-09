<?php

use App\Http\Controllers\AccountActivationController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

// =============================================
// Aktivasi Akun (Publik, tanpa login)
// =============================================
Route::get('/activate', [AccountActivationController::class, 'showForm'])
    ->name('activate.form');

Route::post('/activate/verify', [AccountActivationController::class, 'verify'])
    ->name('activate.verify');

Route::post('/activate/complete', [AccountActivationController::class, 'complete'])
    ->name('activate.complete');

// =============================================
// Report Routes (Memerlukan login)
// =============================================
Route::middleware(['auth'])->group(function () {
    Route::get('/reports/placement/{student}/print', [ReportController::class, 'printStudentJournal'])
        ->name('reports.student.print');

    Route::get('/reports/teacher/{teacher}/assignment-letter', [ReportController::class, 'printTeacherAssignmentLetter'])
        ->name('reports.teacher.letter');
});
