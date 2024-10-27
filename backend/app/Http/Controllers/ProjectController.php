<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('employees')->orderBy('created_at', 'desc')->get();

        return [
            'project' => $projects
        ];
    }

    public function updateRole(Request $request, Project $project, Employee $employee)
    {
        $project->employees()->updateExistingPivot($employee->id, [
            'role' => $request->role
        ]);

        return response(['role' => $request->role, 'employee_id' => $employee->id], Response::HTTP_ACCEPTED);
    }

    public function deleteEmployee(Project $project, Employee $employee)
    {
        $project->employees()->detach($employee->id);

        return response(['employee_id' => $employee->id], Response::HTTP_NO_CONTENT);
    }

    public function getProjects()
    {
        $projects = Project::all();

        return [
            'projects' => $projects
        ];
    }
}
