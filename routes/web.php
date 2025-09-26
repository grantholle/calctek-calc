<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::resource('/calculations', \App\Http\Controllers\CalculationController::class)
    ->only(['store', 'destroy']);
