<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'employee_code',
        'email',
        'phone_number',
        'department_id',
        'position',
        'date_of_hire',
        'salary_rate',
        'employment_type',
        'status',
        'address',
    ];

    protected $casts = [
        'date_of_hire' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
