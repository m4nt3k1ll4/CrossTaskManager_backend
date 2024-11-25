<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\AppUserController;
use App\Http\Controllers\Api\HeadquarterController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\CommentController;

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

//public routes
Route::post('register', [AppUserController::class, 'store']);
Route::post('login', [AuthController::class, 'login']);

//auth routes
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

//scope manage users
Route::middleware('scopes:manage-users')->group(function () {
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('users', AppUserController::class);
    Route::apiResource('headquarters', HeadquarterController::class);
    Route::apiResource('comments', CommentController::class);
    Route::apiResource('tasks', TaskController::class);
    });

 //scope ManageTask
Route::middleware('scopes:manage-tasks,view-tasks')->group(function () {
    Route::apiResource('tasks', TaskController::class);
    Route::get('/tasks-view', [TaskController::class, 'getTasksAndUsers']);
    Route::post('/tasks-assign', [TaskController::class, 'assignTaskToAdviser']);
    Route::put('/tasks-assigned/{adviserTaskId}', [TaskController::class, 'updateAssignedTaskStatus']);
    Route::get('/tasks-assigned', [TaskController::class, 'getAssignedTasks']);
    Route::delete('/tasks-unassign/{adviserTaskId}', [TaskController::class, 'unassignTaskById']);
    Route::get('tasks-images/{taskId}', [TaskController::class, 'getImages']);
    Route::post('tasks-images/{taskId}', [TaskController::class, 'postImages']);
    Route::delete('tasks-images/{taskId}/{imageId}', [TaskController::class, 'deleteImage']);
    Route::apiResource('comments', CommentController::class);
    });

 //Scope ViewTasks
Route::middleware('scopes:view-tasks')->group(function () {
    Route::put('/tasks-assigned/{adviserTaskId}', [TaskController::class, 'updateAssignedTaskStatus']);
    Route::put('/tasks-assigned', [TaskController::class, 'getAssignedTasks']);
    Route::apiResource('comments', CommentController::class);
    Route::get('tasks-images{taskId}', [TaskController::class, 'getImages']);
    Route::post('tasks-images/{taskId}', [TaskController::class, 'postImages']);
    Route::delete('tasks-images/{taskId}/{imageId}', [TaskController::class, 'deleteImage']);
    });
});
