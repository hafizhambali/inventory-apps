<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'dept_id',
        'employee_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }
}
