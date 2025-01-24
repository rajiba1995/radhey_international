<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CollectionController;
use App\Http\Controllers\Api\FabricController;

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
Route::post('/password-recover', [AuthController::class, 'sendResetLink']);
Route::post('/npin-login', [AuthController::class, 'loginWithNpin']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
    

// });
Route::middleware('auth:sanctum', 'token.expiry')->group(function () {
    // Route for creating a product
    Route::get('/user', [UserController::class, 'index']);
    Route::post('/user/store', [UserController::class, 'store']);
    Route::get('/user/list', [UserController::class, 'list']);
    Route::get('/user/search', [UserController::class, 'search']);
    Route::get('/user/show/{id}', [UserController::class, 'show']);




    Route::get('/category', [CategoryController::class, 'index']);
    Route::get('/collection', [CollectionController::class, 'index']);
    Route::get('/fabric', [FabricController::class, 'index']);
    
    // More routes related to products can be added here
    // Route::get('/products', [ProductController::class, 'index']);
    // Route::put('/products/{id}', [ProductController::class, 'update']);
    // Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});