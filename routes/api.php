<?php

use App\Http\Controllers\ApiController;
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

Route::post('/login',[ApiController::class,'login']);
Route::post('/register',[ApiController::class,'register']);
Route::get('/checkUser/{usernameInput}',[ApiController::class,'checkUser']);

Route::get('/getFeed',[ApiController::class,'getFeed']);

Route::get('/profile/{username}',[ApiController::class,'getMyProfileUser']);
Route::get('/profile/{username}/Posts',[ApiController::class,'getMyProfile']);

Route::middleware('auth:api')->group(function () {
    Route::post('/makeComment',[ApiController::class,'makeComment'])->middleware('auth:api');
    Route::post('/insertPost',[ApiController::class,'insertPost'])->middleware('auth:api');
});
