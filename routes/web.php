<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SocialiteController;




require __DIR__.'/auth.php';



use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PatientInformationController;
use App\Http\Controllers\ProviderController;

use App\Http\Middleware\CheckMenuPermission;

Route::middleware(['auth', CheckMenuPermission::class])->group(function () {
    Route::resource('menus', MenuController::class);
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::resource('patients', PatientInformationController::class);
    Route::resource('providers', ProviderController::class);
});


use App\Http\Controllers\DoctorDetailsController;

Route::middleware(['auth'])->group(function () {
    Route::resource('doctor', DoctorDetailsController::class);
});

 
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', function () {  return redirect('/login'); })->name('welcome');

// Ruta para redirigir al usuario a Google
Route::get('auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('google.login');
// Ruta para manejar la respuesta de Google
Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/pago', [PaymentController::class, 'createPayment'])->name('payment.create');
    Route::get('/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/failure', [PaymentController::class, 'failure'])->name('payment.failure');
    Route::get('/pending', [PaymentController::class, 'pending'])->name('payment.pending');
    Route::post('/webhook', [PaymentController::class, 'webhook'])->name('webhook');



});







