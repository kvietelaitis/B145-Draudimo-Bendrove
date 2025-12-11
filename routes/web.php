<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CustomerIncidentController;
use App\Http\Controllers\CustomerOfferController;
use App\Http\Controllers\LeaseCalculatorController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\WorkerIncidentController;
use App\Http\Controllers\WorkerRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/', function () {
    return view('home');
})->middleware('role.redirect');

Route::get('/admin/dashboard', function () {
    $users = \App\Models\Vartotojas::whereIn('role', ['darbuotojas', 'administratorius'])->get();

    return view('admin.dashboard', compact('users'));
})->middleware('check.admin');

Route::get('/customer/dashboard', function () {
    $insurancePolicies = \App\Models\DraudimoPolisas::all();
    $years = Auth::user()->getYearsSinceLastAccident();

    return view('customer.dashboard', compact(['insurancePolicies', 'years']));
})->middleware('check.customer')->name('customer.dashboard');

Route::middleware('check.customer')->group(function () {
    Route::get('/customer/incidents/create', [CustomerIncidentController::class, 'createForm'])->name('customer.incidents.create');
    Route::post('/customer/create-incidents', [CustomerIncidentController::class, 'store']);
    Route::get('/customer/incidents/index', [CustomerIncidentController::class, 'index'])->name('customer.incidents.index');

    Route::post('/customer/choose-policy', [PolicyController::class, 'select']);
    Route::get('/customer/policies/{policy}/packages', [PolicyController::class, 'packages'])->name('customer.policies.packages');
    Route::post('/customer/choose-package', [PolicyController::class, 'choosePackage']);

    Route::get('/customer/offers/index', [CustomerOfferController::class, 'index'])->name('customer.offers.index');
    Route::post('/customer/offers/accept-offer', [CustomerOfferController::class, 'acceptOffer']);
});

Route::middleware('check.worker')->group(function () {
    Route::get('/worker/dashboard', [WorkerController::class, 'index'])->name('worker.dashboard');
    Route::get('worker/incidents/index', [WorkerIncidentController::class, 'index'])->name('worker.incidents.index');

    Route::get('/worker/requests/index', [WorkerRequestController::class, 'index'])->name('worker.requests.index');
    Route::get('/worker/requests/{request}/edit', [WorkerRequestController::class, 'editForm'])->name('worker.request.edit');
    Route::post('/worker/request/update', [WorkerRequestController::class, 'update'])->name('worker.request.update');
    Route::post('/worker/requests/make-offer', [WorkerRequestController::class, 'makeOffer']);
});

Route::post('/register-user', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/register_worker', [AdminUserController::class, 'createWorker']);
Route::post('/remove-worker', [AdminUserController::class, 'removeWorker']);
Route::post('/calculate-lease', [LeaseCalculatorController::class, 'calculate']);
Route::post('/declare-event', [UserController::class, 'reportAccident']);
Route::post('/block-worker', [AdminUserController::class, 'blockWorker']);

Route::get('/wip', function () {
    return view('wip');
})->name('wip');
