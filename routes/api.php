<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PathController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserAnswerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);







Route::middleware(['auth:sanctum','student'])->group(function(){
Route::post('logout',[AuthController::class,'logout']);
Route::apiResource('test',TestController::class);
Route::apiResource('useranswer',UserAnswerController::class);

});



Route::middleware(['auth:sanctum','admin'])->group(function () {
 Route::get('path',[PathController::class,'getpath']);
 Route::apiResource('questions',QuestionController::class);   
});







