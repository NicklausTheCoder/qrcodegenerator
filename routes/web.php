<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QRcodeGenerateController;
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
    return view('welcome');
});

Route::get('/generate', [QRcodeGenerateController::class,'qrcode']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/register-email', [App\Http\Controllers\optController::class, 'opt'])->name('register-email');
Route::Post('/optin', [App\Http\Controllers\optController::class, 'optin'])->name('opt-in');
Route::get('/success', function () {
    return view('success');
})->name('success');