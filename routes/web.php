<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\AdminController;

// Public
Route::get('/',    [VisitorController::class, 'index'])->name('home');
Route::post('/log',[VisitorController::class, 'store'])->name('visitors.store');

// Admin (must be logged in)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',             [AdminController::class, 'dashboard'])->name('dashboard');
    Route::delete('/visitors/{visitor}', [AdminController::class, 'destroy'])->name('visitors.destroy');
    Route::get('/export/excel',          [AdminController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf',            [AdminController::class, 'exportPdf'])->name('export.pdf');
});

require __DIR__.'/auth.php';

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');
