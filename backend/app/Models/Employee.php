<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = [];

    // task 1 - Retrieve all projects an employee is working on, including the role they have in each project.
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'employee_project')->withPivot('role')->as('assignment');
    }
}
