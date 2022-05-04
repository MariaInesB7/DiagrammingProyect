<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentoController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::resource('users',App\Http\Controllers\UserController::class);
Route::resource('participas',App\Http\Controllers\ParticipaController::class);
Route::resource('documentos',App\Http\Controllers\DocumentoController::class);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


