<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    // task 1 - Retrieve all employees assigned to the project, including their roles
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_project')->withPivot('role')->as('assignment');
    }

    // task 1 - Count the number of employees in each role within a project.
    public function scopeEmployeeRolesCount(Builder $query)
    {
        return $query->withCount([
            'employees as roles_count' => function (Builder $query) {
                $query->select('role', DB::raw('count(*) as count'))
                    ->groupBy('role');
            }
        ]);
    }

    // task 2 - Write a query to find all projects that have a specific role assigned to any employee.
    public function scopeWithEmployeeRole(Builder $query, string $role)
    {
        return $query->whereHas('employees', function ($query) use ($role) {
            $query->where('role', $role);
        });
    }
}
