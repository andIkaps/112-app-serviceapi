<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Master\DistrictController;
use App\Http\Controllers\Master\EmployeeController;
use App\Http\Controllers\Master\ReligionController;
use App\Http\Controllers\Master\StatusController;
use App\Http\Controllers\RBAC\MenuController;
use App\Http\Controllers\RBAC\PermissionController;
use App\Http\Controllers\RBAC\RoleController;
use App\Http\Controllers\RBAC\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::prefix('/v1')->group(function () {
    Route::get('/login',  function (Request $request) {
        return response()->json([
            'error' => 'Token is blacklisted. Please log in again.',
        ], 401);
    })->name('login');

    Route::post('/auth/login',  [AuthController::class, 'login'])->name('post-login');
    Route::post('/auth/refresh-token',  [AuthController::class, 'refresh'])->name('refresh-token');
    Route::post('/auth/logout',  [AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth:api')->group(function () {
        // Master
        Route::post('/employees/{employee}', [EmployeeController::class, 'update']);
        Route::apiResource('/employees', EmployeeController::class)->except('update');
        Route::apiResource('/marital-status', StatusController::class)->except(['show']);
        Route::apiResource('/religions', ReligionController::class)->except(['show']);
        Route::apiResource('/districts', DistrictController::class)->except(['show']);

        // RBAC
        Route::apiResource('/users', UserController::class);
        Route::apiResource('/roles', RoleController::class);
        Route::apiResource('/menus', MenuController::class);
        Route::apiResource('/permissions', PermissionController::class);
    });
});
