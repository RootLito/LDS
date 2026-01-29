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
        'skills'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'skills' => 'array',
    ];

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }


    public function trainingsAttended()
    {
        return $this->hasMany(TrainingAttended::class, 'emp_id');
    }

}
