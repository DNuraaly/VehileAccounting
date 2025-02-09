<?php

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


use App\Http\Controllers\CarController;

Route::get('/', [CarController::class, 'index'])->name('cars.index');
Route::post('/cars/register', [CarController::class, 'registerCar'])->name('cars.register');
Route::get('/cars/{carNumber}/history', [CarController::class, 'showOwnerHistory'])->name('cars.showHistory');
Route::get('/cars/models', [CarController::class, 'showCarModels'])->name('cars.models');
Route::post('/cars/{autoNumber}/re-register', [CarController::class, 'reRegisterCar'])->name('cars.reRegister');

