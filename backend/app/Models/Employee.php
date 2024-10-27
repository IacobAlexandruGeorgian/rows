<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'employee_project')->withPivot('role')->as('assignment');
    }

    public function scopeWithProjectsAndRole(Builder $query)
    {
        return $query->with(['projects' => function ($query) {
            $query->select('projects.id', 'projects.name')
                ->withPivot('role')->as('assignment');
        }]);
    }
}
