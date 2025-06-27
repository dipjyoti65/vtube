<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OtpAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;

Route::post('/send-otp', [OtpAuthController::class, 'sendOtp']);
Route::post('/verify-otp', [OtpAuthController::class, 'verifyOtp']);


// Route::middleware('auth:api')->get('/protected', function () {
//     return response()->json(['message' => 'Access granted']);
// });

Route::middleware('auth:api')->group(function () {
    Route::get('/protected', function () {
        return response()->json(['message' => 'Access granted']);
    });

    Route::put('/update-profile',[UserController::class, 'updateProfile']);

    Route::get('/get-profile',[UserController::class,'getProfile']);

    Route::post('/logout',[OtpAuthController::class, 'logout']);

    Route::post('/upload-chunk', [VideoController::class, 'uploadChunk']);

    Route::post('/merge-chunks', [VideoController::class, 'mergeChunks']);

    Route::get('/get-user-videos', [VideoController::class, 'getUserVideos']);

  

});

// route without auth
Route::get('/get-all-videos', [VideoController::class, 'getAllVideos']);