<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;

Route::get('/', function () {
    // return view('welcome');
     return 'Laravel is working!';
});

Route::get('/get-all-videos', [VideoController::class, 'getAllVideos']);