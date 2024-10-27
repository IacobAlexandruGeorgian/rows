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

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_project')->withPivot('role')->as('assignment');
    }

    public function scopeEmployeeRolesCount(Builder $query)
    {
        return $query->withCount([
                'employees as roles_count' => function (Builder $query) {
                    $query->select('role', DB::raw('count(*) as count'))
                        ->groupBy('role');
                }
            ]);
    }

    public function scopeWithEmployeeRole(Builder $query, string $role)
    {
        return $query->whereHas('employees', function ($query) use ($role) {
            $query->where('role', $role);
        });
    }
}
