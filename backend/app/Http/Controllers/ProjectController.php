<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    // task 2 - Write a controller method to retrieve a list of projects along with the employees assigned to each project and their roles.
    public function index()
    {
        $projects = Project::with('employees')->orderBy('created_at', 'desc')->get();

        return [
            'projects' => $projects
        ];
    }

    // task 3 - Write a function to update an employee’s role within a specific project.
    public function updateRole(Request $request, $project_id, $employee_id)
    {
        $project = Project::findOrFail($project_id);

        $project->employees()->updateExistingPivot($employee_id, [
            'role' => $request->role
        ]);

        return response(['role' => $request->role, 'employee_id' => $employee_id], Response::HTTP_ACCEPTED);
    }

    // task 3 - Write a function to remove an employee from a project.
    public function deleteEmployee($project_id, $employee_id)
    {
        $project = Project::findOrFail($project_id);

        $project->employees()->detach($employee_id);

        return [
            'employee_id' => $employee_id
        ];
    }

    public function search(Request $request)
    {
        $projects = Project::withEmployeeRole($request->role)->get();

        return [
            'projects' => $projects
        ];
    }

    // task bonus
    public function getProjects()
    {
        $projects = Project::all();

        return [
            'projects' => $projects
        ];
    }
}
