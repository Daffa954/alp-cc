<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('expenses', ExpenseController::class);
    
    // Quick add expense
    Route::post('/expenses/quick-add', [ExpenseController::class, 'quickAdd'])
        ->name('expenses.quick-add');
        
    // Export expenses
    Route::get('/expenses/export', [ExpenseController::class, 'export'])
        ->name('expenses.export');
});
require __DIR__.'/auth.php';
