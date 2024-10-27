<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/employees', [EmployeeController::class, 'index']);
Route::get('/projects', [ProjectController::class, 'getProjects']);
Route::post('/employee/store', [EmployeeController::class, 'store']);
