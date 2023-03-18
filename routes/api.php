<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PreferencesController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register',[AuthController::class,'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::get('/news', [NewsController::class, 'news']);

Route::get('/search', [NewsController::class, 'search']);

Route::middleware('auth:sanctum')->get('/news-by-preferences', [PreferencesController::class, 'preferences']);

Route::middleware('auth:sanctum')->post('/update-user-info', [AuthController::class, 'updateUserInfo']);
Route::middleware('auth:sanctum')->post('/update-user-password', [AuthController::class, 'changePassword']);
