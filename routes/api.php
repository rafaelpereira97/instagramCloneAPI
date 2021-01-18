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


Route::post('/login',[ApiController::class,'login']);
Route::post('/register',[ApiController::class,'register']);
Route::get('/checkUser/{usernameInput}',[ApiController::class,'checkUser']);

Route::get('/getFeed',[ApiController::class,'getFeed']);

Route::get('/profile/{username}',[ApiController::class,'getMyProfileUser']);
Route::get('/profile/{username}/Posts',[ApiController::class,'getMyProfile']);
Route::get('/forgotPassword/{email}',[ApiController::class,'forgotPassword']);

Route::middleware('auth:api')->group(function () {
    Route::post('/makeComment',[ApiController::class,'makeComment']);
    Route::post('/insertPost',[ApiController::class,'insertPost']);
    Route::post('/changeAvatar',[ApiController::class,'changeAvatar']);
});
