<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClassController;
use App\Http\Controllers\Api\AttendanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Teacher Routes
    Route::get('/teacher/classes', [ClassController::class, 'index']);
    Route::post('/classes', [ClassController::class, 'store']);
    Route::post('/classes/{id}/end', [ClassController::class, 'end']);
    Route::get('/classes/{id}/attendance', [ClassController::class, 'attendance']);
    Route::get('/classes/{id}', [ClassController::class, 'show']);

    // Student Routes
    Route::post('/attendance', [AttendanceController::class, 'store']);
});
