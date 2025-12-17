<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get(
    '/dashboard',
    [DashboardController::class, 'index']
)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('expenses', ExpenseController::class);
    Route::resource('activities', ActivityController::class);
    Route::resource('incomes', IncomeController::class);
    // Halaman Report Utama
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    // Action Generate AI (POST)
    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');
    // --- FITUR 2: CHATBOT ADVISOR ---
    // Halaman Chat
    // Route::get('/advisor', function () {
    //     return view('advisor.index');
    // })->name('advisor.index');
    // // API Endpoint untuk kirim pesan ke AI
    // Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
});
Route::get('/debug-gemini-models', function () {
    $apiKey = trim(env('GEMINI_API_KEY'));
    
    // Request ke endpoint 'models' (GET) untuk melihat daftar yang tersedia
    $response = Http::get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");
    
    return $response->json();
});
require __DIR__ . '/auth.php';
