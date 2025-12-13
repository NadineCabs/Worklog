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
        'shift_id'
    ];

    protected $casts = [
        'date_of_hire' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}