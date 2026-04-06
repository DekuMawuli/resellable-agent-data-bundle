<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PayStackController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [PagesController::class, 'index'])->name('pages.home');
Route::get('products', [PagesController::class, 'allProducts'])->name('pages.products');
Route::get('login', [PagesController::class, 'login'])->name('pages.login');
Route::post('process-login', [PagesController::class, 'processLogin'])->name('pages.processLogin');
Route::get('register', [PagesController::class, 'register'])->name('pages.register');
Route::post('process-signup', [PagesController::class, 'processRegistration'])->name('pages.processSignUp');
Route::get('forgot-password', [PasswordResetController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password/verify-phone', [PasswordResetController::class, 'verifyPhone'])->name('password.phone.verify');
Route::post('forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('reset-password/{token}', [PasswordResetController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');

Route::prefix('/pages')
    ->controller(PagesController::class)
    ->as('pages.')
    ->middleware('auth')
    ->group(function () {
        Route::post('/logout', 'logout')->name('logout');
        Route::get('/profile', 'profile')->name('profile');
    });

Route::prefix('/root')
    ->controller(AdminController::class)
    ->as('root.')
    ->middleware('admin')
    ->group(function () {
        Route::get('', 'dashboard')->name('dashboard');
        Route::get('/categories', 'categories')->name('categories');
        Route::get('/products', 'products')->name('products');
        Route::get('/orders', 'orders')->name('orders');
        Route::post('/toggle-balance', 'toggleBalanceView')->name('view-balance');
        Route::delete('/delete-order/{code}', 'deleteOrder')->name('deleteOrder');
        Route::get('/agents', 'agents')->name('agents');
        Route::get('/agent-detail/{code}', 'agentDetail')->name('agent_detail');
        Route::post('/approve-deposit/{id}', 'approveDeposit')->name('approveDeposit');
        Route::post('/confirm-payment/{id}', 'confirmPayment')->name('confirmPayment');
        Route::post('/approve-purchase/{id}', 'approvePurchase')->name('approvePurchase');
        Route::post('/confirm-purchase/{id}', 'confirmPurchase')->name('confirmPurchase');
        Route::get('/settings', 'settings')->name('settings');
        Route::get('/credentials', 'credentials')->name('credentials');
    });

Route::get('agent/ps/verify-payment', [PayStackController::class, 'handlePaymentCallback'])
    ->name('agent.handle-payment-callback');

Route::prefix('/agent')
    ->controller(AgentController::class)
    ->as('agent.')
    ->middleware('agent')
    ->group(function () {
        Route::get('', 'dashboard')->name('dashboard');
        Route::get('/orders', 'orders')->name('orders');
        Route::get('/products', 'products')->name('products');

        Route::post('/deposit', 'deposit')->name('deposit');
        Route::post('/confirm-payment', 'confirmPayment')->name('confirmPayment');
        Route::post('ps/init-payment', [PayStackController::class, 'initPayment'])->name('initPayment');
        Route::post('ps/confirm-payment', [PayStackController::class, 'verifyPayment'])->name('confirm-payment');

    });
