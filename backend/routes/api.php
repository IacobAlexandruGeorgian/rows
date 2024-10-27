<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// task bonus
Route::get('/employees', [EmployeeController::class, 'index']);
Route::get('/projects', [ProjectController::class, 'getProjects']);
Route::post('/employee/store', [EmployeeController::class, 'store']);
Route::get('/projects-with-employees', [ProjectController::class, 'index']);
Route::delete('/project/{project_id}/employee/{employee_id}', [ProjectController::class, 'deleteEmployee']);
Route::put('/project/{project_id}/employee/{employee_id}/role/update', [ProjectController::class, 'updateRole']);
Route::post('/project/search', [ProjectController::class, 'search']);
