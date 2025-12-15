<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $table = 'shifts';
    
    // Use 'id' as primary key (Laravel convention)
    protected $primaryKey = 'id';

    protected $fillable = [
    'employee_id',  // Add this
    'shift_name',
    'start_time',
    'end_time',
];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    // Relationship: A shift can have many employees
    
    public function employee()
{
    return $this->belongsTo(Employee::class);
}
}