<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'gender',
        'birthdate',
        'appointment_date',
        'status',
        'username',
        'password',
        'profile',
        'position',
        'office',
        'designation',
    ];

    protected $hidden = [
        'password',
    ];

    public function trainingsAttended()
    {
        return $this->hasMany(TrainingAttended::class, 'emp_id');
    }
}
