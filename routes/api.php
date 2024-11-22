<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\AppUserController;
use App\Http\Controllers\Api\HeadquarterController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\PermissionController;
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
Route::apiResource('permissions', PermissionController::class);
Route::post('register', [AppUserController::class, 'store']);
Route::post('login', [AuthController::class, 'login']);

//auth routes
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

//CEO routes
Route::middleware('scopes:manage-users')->group(function () {
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('users', AppUserController::class);
    Route::apiResource('headquarters', HeadquarterController::class);
    Route::apiResource('comments', CommentController::class);
    Route::apiResource('tasks', TaskController::class);
    });

//Task Routes
Route::middleware('scopes:manage-tasks,view-tasks')->group(function () {
    Route::apiResource('tasks', TaskController::class);
    Route::get('/tasks-view', [TaskController::class, 'getTasksAndUsers']);
    Route::post('/tasks-assign', [TaskController::class, 'assignTaskToAdviser']);
    Route::delete('/tasks/unassign/{adviserTaskId}', [TaskController::class, 'unassignTaskById']);
    Route::get('tasks/{taskId}/images', [TaskController::class, 'getImages']);
    Route::post('tasks/{taskId}/images', [TaskController::class, 'postImages']);
    Route::delete('tasks/{taskId}/images/{imageId}', [TaskController::class, 'deleteImage']);
    Route::apiResource('comments', CommentController::class);
    });

//Advisers
Route::middleware('scopes:view-tasks')->group(function () {
    Route::get('tasks/{id}', [TaskController::class, 'show']);
    Route::get('/tasks-assigned', [TaskController::class, 'getAssignedTasks']);
    Route::put('tasks/{id}',[TaskController::class, 'update']);
    Route::apiResource('comments', CommentController::class);
    });
});
