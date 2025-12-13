<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $table = 'shifts';
    protected $primaryKey = 'id';

    protected $fillable = [
        'shift_name',
        'start_time',
        'end_time',
        'description'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    // Relationship: A shift can have many employees
    public function employees()
    {
        return $this->hasMany(Employee::class, 'shift_id');
    }
}