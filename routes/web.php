<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\LeaseCalculatorController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/', function () {
    return view('home');
})->middleware('role.redirect');

Route::get('/admin/dashboard', function () {
    $users = \App\Models\Vartotojas::whereIn('role', ['darbuotojas', 'administratorius'])->get();
    return view('/admin/dashboard', compact('users'));
})->middleware('check.admin');

Route::get('/customer/dashboard', function () {
    $insurancePolicies = \App\Models\DraudimoPolisas::all();
    $years = Auth::user()->getYearsSinceLastAccident();
    return view('customer.dashboard', compact(['insurancePolicies', 'years']));
})->middleware('check.customer');

Route::get('/worker/dashboard', function () {
    return view('worker.dashboard');
})->middleware('check.worker');

Route::post('/register-user', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/register_worker', [AdminUserController::class, 'createWorker']);
Route::post('/remove-worker', [AdminUserController::class, 'removeWorker']);
Route::post('/calculate-lease', [LeaseCalculatorController::class, 'calculate']);
Route::post('/declare-event', [UserController::class, 'reportAccident']);
Route::post('/choose-policy', function () {
    return view('wip');
});
