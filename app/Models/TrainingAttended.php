<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingAttended extends Model
{
    use HasFactory;

    protected $table = 'training_attended';

    protected $fillable = [
        'emp_id',
        'title',
        'date',
        'duration',
        'type',
        'sponsored',
        'certificate_path',
    ];



    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }
}
