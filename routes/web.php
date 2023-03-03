<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::get('/approval/{hash}', [OrderController::class, 'approval']);
