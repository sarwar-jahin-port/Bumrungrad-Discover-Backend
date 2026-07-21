<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientStory extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name',
        'country',
        'rating',
        'story',
        'status',
    ];
}
