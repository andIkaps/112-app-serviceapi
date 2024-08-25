<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Master\EmployeeController;
use App\Http\Controllers\Master\StatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::prefix('/v1')->group(function () {
    Route::post('/auth/register',  [AuthController::class, 'register'])->name('register');

    Route::apiResource('/employees', EmployeeController::class);
    Route::apiResource('/status', StatusController::class)->except(['show']);
});
