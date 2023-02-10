<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DojaController;
use App\Http\Controllers\FlutterwaveController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UserVerificationController;
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

Route::middleware('auth:sanctum')->get('user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->post('user', [AuthController::class, 'updateUser']);

Route::post('login',    [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::post('password/reset/submit',    [AuthController::class, 'reset_password']);
Route::post('password/forgot',          [AuthController::class, 'forgot_password']);

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::post('send-otp',         [DojaController::class, 'verify']);
    Route::post('verify-otp',       [DojaController::class, 'validate_otp']);
    Route::get('me',                [AuthController::class, 'getUser']);
    /**
     * Get user wallet balance
     */
    Route::get("wallet/balance",    [AuthController::class, 'balance']);

    Route::group(["prefix" => 'verify'],    function(){
        Route::get('status',    [UserVerificationController::class, 'getStatus']);
        Route::post('submit',   [UserVerificationController::class, 'verify']);
    });

    Route::group(["prefix" => 'property'],    function(){
        Route::get('preview/{id}',  [PropertyController::class, 'show']);
        Route::get('list',          [PropertyController::class, 'list']);
        Route::post('submit',       [PropertyController::class, 'store']);
        Route::post('update/{id}',  [PropertyController::class, 'update']);
    });
});


Route::fallback(function(){
    return get_error_response([
        'message' => 'Page Not Found. If error persists, contact support'
    ], 404);
});