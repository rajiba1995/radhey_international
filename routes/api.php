<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/generate-otp', [AuthController::class, 'generateOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::put('/forget-password', [AuthController::class, 'sendResetLink']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
    

// });
Route::middleware('auth:sanctum')->group(function () {
    // Route for creating a product
    Route::get('/', [UserController::class, 'index']);
    // More routes related to products can be added here
    // Route::get('/products', [ProductController::class, 'index']);
    // Route::put('/products/{id}', [ProductController::class, 'update']);
    // Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});