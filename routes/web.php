<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
