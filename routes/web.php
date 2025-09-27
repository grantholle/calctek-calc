<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\CalculatorController::class)
    ->name('calculator');

Route::resource('/calculations', \App\Http\Controllers\CalculationController::class)
    ->only(['index', 'store', 'destroy']);

Route::delete('/calculations', \App\Http\Controllers\DeleteAllCalculationsController::class)
    ->name('calculations.delete-all');
