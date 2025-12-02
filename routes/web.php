<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\SummaryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/about', function () {
    return view('secondPage');
});

Route::get('/login', function () {
    return view('auth.login'); 
})->name('login');

Route::get('/register', function () {
    return view('auth.register'); 
})->name('register');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/income/add', function () {
    return view('income');
})->name('income.create');

Route::get('/expense/add', function () {
    return view('expense');
})->name('expense.create');