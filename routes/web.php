<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register.show');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Books
Route::resource('books', BookController::class)->middleware('auth');
Route::resource('transactions', TransactionController::class)->middleware('auth');
// jjk

// Users (only admin can manage, and only users with role anggota will be listed/managed)
Route::resource('users', UserController::class)->except(['show'])->middleware('auth');
