<?php

use App\Http\Middleware\VerifyVtexRequest;
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

Route::get('manifest', '\App\Http\Controllers\Controller@manifest')->name('manifest')->middleware(VerifyVtexRequest::class);
Route::post('payments', '\App\Http\Controllers\Controller@payments')->name('payments')->middleware(VerifyVtexRequest::class);
Route::get('paymentMethods', '\App\Http\Controllers\Controller@getPaymentMethods')->name('getPaymentMethods');
