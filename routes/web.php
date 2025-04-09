<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Forms\MaintainFormController;
use App\Http\Controllers\Forms\RelationFormController;
Route::get('/', function () {
    return view('dashboard');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup']);
Route::get('/price', function() { return view('price'); });
Route::resource('/forms/maintain', MaintainFormController::class);
Route::resource('/forms/relation', RelationFormController::class);