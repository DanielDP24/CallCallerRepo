<?php

use App\Http\Controllers\CallController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
