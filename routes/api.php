<?php

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
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Add a new task
Route::post('addTask', [UserController::class, 'addTask']);
//Get list of tasks
Route::get('getTasks', [UserController::class, 'getTasks']);
//Delete a specific task
Route::delete('deleteTask', [UserController::class, 'deleteTask']);
//Update the status to a specific task
Route::put('updateState', [UserController::class, 'updateTaskStatus']);
//Update task
Route::put('updateTask', [UserController::class, 'updateTask']);