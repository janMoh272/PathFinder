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


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');




Route::middleware('auth:sanctum')->group(function(){
Route::post('logout',[AuthController::class,'logout']);

Route::apiResource('test',TestController::class);
Route::apiResource('useranswer',UserAnswerController::class);
//Route::get('test',[TestController::class, 'index']);
});






Route::get('path',[PathController::class,'getpath']);

Route::apiResource('questions',QuestionController::class);




