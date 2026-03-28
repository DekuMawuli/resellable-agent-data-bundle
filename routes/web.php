<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayStackController;

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

Route::get("/", [PagesController::class, "index"])->name("pages.home");
Route::get("products", [PagesController::class, "allProducts"])->name("pages.products");
Route::get("login", [PagesController::class, "login"])->name("pages.login");
Route::post("process-login", [PagesController::class, "processLogin"])->name("pages.processLogin");
Route::get("register", [PagesController::class, "register"])->name("pages.register");
Route::post("process-signup", [PagesController::class, "processRegistration"])->name("pages.processSignUp");




Route::prefix("/pages")
    ->controller(PagesController::class)
    ->as("pages.")
    ->middleware("auth")
    ->group(function (){
        Route::get("/logout", "logout")->name("logout");
        Route::get("/profile", "profile")->name("profile");
    });

Route::prefix("/root")
    ->controller(\App\Http\Controllers\AdminController::class)
    ->as("root.")
    ->middleware("admin")
    ->group(function (){
        Route::get("", "dashboard")->name("dashboard");
        Route::get("/categories", "categories")->name("categories");
        Route::get("/products", "products")->name("products");
        Route::get("/orders", "orders")->name("orders");
        Route::post("/toggle-balance", "toggleBalanceView")->name("view-balance");
        Route::get("/delete-order/{code}", "deleteOrder")->name("deleteOrder");
        Route::get("/agents", "agents")->name("agents");
        Route::get("/agent-detail/{code}", "agentDetail")->name("agent_detail");
        Route::get("/approve-deposit/{id}", "approveDeposit")->name("approveDeposit");
        Route::get("/confirm-payment/{id}", "confirmPayment")->name("confirmPayment");
        Route::get("/approve-purchase/{id}", "approvePurchase")->name("approvePurchase");
        Route::get("/confirm-purchase/{id}", "confirmPurchase")->name("confirmPurchase");
        Route::get("/settings", "settings")->name("settings");
        Route::get("/credentials", "credentials")->name("credentials");
    });


Route::prefix("/agent")
    ->controller(\App\Http\Controllers\AgentController::class)
    ->as("agent.")
    ->middleware("agent")
    ->group(function (){
        Route::get("", "dashboard")->name("dashboard");
        Route::get("/orders", "orders")->name("orders");
        Route::get("/products", "products")->name("products");

        Route::post("/deposit", "deposit")->name("deposit");
        Route::get("/confirm-payment", "confirmPayment")->name("confirmPayment");
        Route::post("ps/init-payment", [PayStackController::class, "initPayment"])->name("initPayment");
        Route::get("ps/verify-payment", [PayStackController::class, "handlePaymentCallback"])->name("handle-payment-callback");
        Route::post("ps/confirm-payment", [PayStackController::class, "verifyPayment"])->name("confirm-payment");

    });
