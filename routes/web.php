<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/transaction', [TransactionController::class, 'create'])->name('transaction.create');
Route::post('/account/withdraw', [AccountController::class, 'withdraw'])->name('account.withdraw');
