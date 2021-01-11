<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login',[\App\Http\Controllers\ApiController::class,'login']);
Route::post('/register',[\App\Http\Controllers\ApiController::class,'register']);
Route::get('/checkUser/{usernameInput}',[\App\Http\Controllers\ApiController::class,'checkUser']);

Route::post('/insertPost',[\App\Http\Controllers\ApiController::class,'insertPost'])->middleware('auth:api');
Route::get('/getFeed',[\App\Http\Controllers\ApiController::class,'getFeed'])->middleware('auth:api');

Route::post('/makeComment',[\App\Http\Controllers\ApiController::class,'makeComment'])->middleware('auth:api');
Route::get('/profile/{username}',[\App\Http\Controllers\ApiController::class,'getMyProfileUser']);
Route::get('/profile/{username}/Posts',[\App\Http\Controllers\ApiController::class,'getMyProfile']);
