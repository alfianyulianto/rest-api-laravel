<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/users', [UserController::class, 'users']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Route::get('/users/profile', function (Request $request) {
//   return auth()->user();
// })->middleware('auth:sanctum');
Route::get('/users/profile', [UserController::class, 'profile'])->middleware('auth:sanctum');

// User yang memiliki Credentials bisa membuka postingan User lain
Route::get('/users/{id}', [UserController::class, 'profileById'])->middleware('auth:sanctum');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/post', [PostController::class, 'add'])->middleware('auth:sanctum');

// /post/{id} : mengirimkan sebuah ID untuk di gunakann pada method didalam Controller
Route::put('/post/{id}', [PostController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/post/{id}', [PostController::class, 'delete'])->middleware('auth:sanctum');
