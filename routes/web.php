<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthSessionController;
use App\Http\Controllers\TwoFactorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});
Route::get('/login', [AuthSessionController::class, 'index'])
    ->middleware(['guest', 'recaptcha'])
    ->name('login');

Route::post('/login', [AuthSessionController::class, 'store'])
    ->middleware(['guest', 'recaptcha']);

Route::middleware([
    'auth:sanctum',
    '2fa',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    // Add 2FA routes
    Route::get('/two-factor-challenge', function () {
        return view('auth.two-factor-challenge');
    })->name('two-factor.login');

    Route::post('/two-factor-challenge', [TwoFactorController::class, 'store'])
    ->name('two-factor.post');
});
