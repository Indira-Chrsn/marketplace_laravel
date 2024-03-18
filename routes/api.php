<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Route::get('/products', [ProductController::class,'index']);


Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post('logout', [LoginController::class, 'logout']);
    Route::get('user', [LoginController::class, 'user']);

    Route::put('/products/{id}/restore', [ProductController::class, 'restore']);
    Route::resource('/products', ProductController::class);

    Route::put('/category/{id}/restore', [CategoryController::class, 'restore']);
    Route::resource('/categories', CategoryController::class);
});

//Route::get('search', [ProductController::class, 'search']);