<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CollectionController;
use App\Http\Controllers\Api\FabricController;
use App\Http\Controllers\Api\BusinessTypeController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;

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
Route::post('/mpin-login', [AuthController::class, 'loginWithMpin']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
    

// });
// Route::middleware('auth:sanctum', 'token.expiry')->group(function () {
Route::middleware('auth:sanctum', 'token.session')->group(function () {
    // Route for creating a product
    Route::get('/user', [UserController::class, 'index']);
    Route::post('/user/store', [UserController::class, 'store']);
    Route::get('/user/list', [UserController::class, 'list']);
    Route::get('/user/search', [UserController::class, 'search']);
    Route::get('/user/show/{id}', [UserController::class, 'show']);




    Route::get('/category', [CategoryController::class, 'index']);
    Route::get('/collection', [CollectionController::class, 'index']);
    Route::get('/fabric', [FabricController::class, 'index']);
    Route::get('/business-type', [BusinessTypeController::class, 'index']);
    Route::get('/category/category-collection-wise/{categoryid}', [CategoryController::class, 'getCategoriesByCollection']);
    Route::get('/product/products-category-collection-wise', [ProductController::class, 'getProductsByCategoryAndCollection']);
    Route::get('/product/products-collection-wise', [ProductController::class, 'getProductsByCollection']);
    
    Route::post('/order/store', [OrderController::class, 'createOrder']);
    Route::get('/order/list', [OrderController::class, 'index']);
    // More routes related to products can be added here
    // Route::get('/products', [ProductController::class, 'index']);
    // Route::put('/products/{id}', [ProductController::class, 'update']);
    // Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});