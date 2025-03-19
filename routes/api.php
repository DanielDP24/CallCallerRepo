<?php

use App\Http\Controllers\CallController;
use Illuminate\Support\Facades\Route;


Route::post("/SayName", [CallController::class, 'SayName']);
Route::post("/SayYes", [CallController::class, 'SayYes']);
Route::post("/SayEmail", [CallController::class, 'SayEmail']);
Route::post("/SayYesEmail", [CallController::class, 'SayYesEmail']);
Route::post("/SayCompany", [CallController::class, 'SayCompany']);
Route::post("/SayYesCompany", [CallController::class, 'SayYesCompany']);