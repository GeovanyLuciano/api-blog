<?php

use App\Http\Controllers\Api\Auth\AuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("v1/register",[AuthenticationController::class,"register"]);
Route::post("v1/login",[AuthenticationController::class,"login"]);


Route::middleware(["auth:sanctum"])->prefix("v1")->group(function(){
    Route::get("/profile",[AuthenticationController::class,"profile"]);
    Route::post("/logout",[AuthenticationController::class,"logout"]);

});
