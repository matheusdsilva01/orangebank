<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FixedIncomeController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function (): void {
    // Account
    Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/transfer', [AccountController::class, 'transferForm'])->name('transfer');
    Route::post('/internal-transfer', [AccountController::class, 'internalTransfer'])->name('transfer.internal');
    Route::post('/external-transfer', [AccountController::class, 'externalTransfer'])->name('transfer.external');
    Route::get('/withdraw', [AccountController::class, 'withdrawForm'])->name('withdraw-form');
    Route::post('/withdraw', [AccountController::class, 'withdraw'])->name('account.withdraw');
    Route::get('/deposit', [AccountController::class, 'depositForm'])->name('deposit-form');
    Route::post('/deposit', [AccountController::class, 'deposit'])->name('account.deposit');
    Route::get('/my-assets', [AssetController::class, 'myAssets'])->name('my-assets');

    // Goals
    Route::get('/goals', [GoalController::class, 'index'])->name('goals');

    // Assets
    Route::get('/assets', [AssetController::class, 'index'])->name('assets');

    // Stock
    Route::get('/stock/{id}', [StockController::class, 'detail'])->name('stock.detail');
    Route::get('/stock-purchased/{id}', [StockController::class, 'detailPurchased'])->name('stock.detail-purchased');
    Route::post('/stock/{stock}', [StockController::class, 'buy'])->name('stock.buy');
    Route::post('/stock-purchased/{accountStock}/sell', [StockController::class, 'sell'])->name('stock.sell-purchased');

    // Fixed Income
    Route::get('/fixed-income/{fixedIncome}', [FixedIncomeController::class, 'detail'])->name('fixed-income.detail');
    Route::get('/fixed-purchased/{id}', [FixedIncomeController::class, 'detail'])->name('fixed-income.detail-purchased');
    Route::post('/fixed-income/{id}', [FixedIncomeController::class, 'buy'])->name('fixed-income.buy');
    Route::post('fixed-purchased/{accountFixedIncome}/sell', [FixedIncomeController::class, 'sell'])->name('fixed-income.sell');
});

// Auth
Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
