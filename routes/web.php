<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http; // Pastikan import ini ada untuk debug route di bawah

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

    // --- PERBAIKAN URUTAN ROUTE REPORTS ---
    
    // 1. Route Khusus/Static WAJIB DI ATAS Route Resource
    Route::get('/reports/history', [ReportController::class, 'history'])->name('reports.history');
    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');

    // 2. Route Resource (Menangani /reports/{id}) WAJIB DI BAWAH
    // Ini menangani index, store, dan show secara otomatis
    Route::resource('reports', ReportController::class)->only(['index', 'store', 'show']);

    // (Baris di bawah ini SAYA HAPUS karena sudah ditangani otomatis oleh 'resource' -> 'show')
    // Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');

    // --- FITUR 2: CHATBOT ADVISOR ---
    Route::get('/advisor', function () {
        return view('advisor.index');
    })->name('advisor.index');
    
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
});

Route::get('/debug-gemini-models', function () {
    $apiKey = trim(env('GEMINI_API_KEY'));
    $response = Http::get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");
    return $response->json();
});
// routes/web.php
Route::get('/test-email-preview', function () {
    $user = \App\Models\User::first() ?? new \App\Models\User([
        'name' => 'DAFFA KHOIRUL FAIZ',
        'email' => 'dkhoirul05@gmail.com'
    ]);
    
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => 1, 'hash' => sha1('test@example.com')]
    );
    
    // Untuk Solusi 1
   
    // Untuk Solusi 4
    return view('emails.verify-email-html', compact('user', 'verificationUrl'));
});
require __DIR__ . '/auth.php';