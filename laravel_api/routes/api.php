<?php

use App\Http\Controllers\RegisteredController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//protect routes using sanctum
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//public 
Route::post('/login',[SessionController::class,'store']);
Route::post('/signup',[RegisteredController::class,'store']);



//protected

Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::post('/logout',[SessionController::class,'destroy']);
    Route::apiResource('tasks', TaskController::class);
    Route::put('complete-all', [TaskController::class, 'completeAll']);
    Route::delete('delete-all', [TaskController::class, 'deleteAll']);
}
);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::put('/tasks/{id}', [TaskController::class, 'update']);
//     Route::patch('/tasks/{id}', [TaskController::class, 'update']);
// });
