<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'status',
        'duration',
        'conducted_by',
        'charging_of_funds',
        'name_of_nominees',
        'number_of_nominees',
        'endorsed_by',
        'hrdc_resolution_no',
        'applicable_for',
        'applicable_skills'
    ];

    protected $casts = [
        'applicable_skills' => 'array',
    ];

    public function attendees()
    {
        return $this->belongsToMany(Employee::class, 'training_attended', 'training_id', 'emp_id')
            ->withTimestamps();
    }
}
