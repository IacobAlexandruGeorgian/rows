<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('projects')->orderBy('created_at', 'desc')->get();

        return [
            'employees' => $employees
        ];
    }

    public function store(Request $request)
    {
        $employee = Employee::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone
        ]);

        foreach ($request->projects as $project) {
            $employee->projects()->attach($project['id'], ['role' => $project['role']]);
        }

        response(['employee' => $employee, 'message' => 'The employee was created successfuly'], Response::HTTP_CREATED);
    }
}
