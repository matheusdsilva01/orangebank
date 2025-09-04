<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/transfer', [AccountController::class, 'transferForm'])->name('transfer');
    Route::post('/internal-transfer', [AccountController::class, 'internalTransfer'])->name('transfer.internal');
    Route::post('/external-transfer', [AccountController::class, 'transfer'])->name('transfer.external');
    Route::get('/withdraw', [AccountController::class, 'withdrawForm'])->name('withdraw-form');
    Route::post('/withdraw', [AccountController::class, 'withdraw'])->name('withdraw');
    Route::get('/deposit', [AccountController::class, 'depositForm'])->name('deposit-form');
    Route::post('/deposit', [AccountController::class, 'deposit'])->name('deposit');
});

Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/transaction', [TransactionController::class, 'create'])->name('transaction.create');
Route::post('/account/withdraw', [AccountController::class, 'withdraw'])->name('account.withdraw');
Route::post('/account/deposit', [AccountController::class, 'deposit'])->name('account.deposit');
