<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'cover_photo',
        'specialty',
        'sub_specialty',
        'lang',
        'gender',
        'schools',
        'certificates',
        'fellowships',
        'interests',
        'experiences',
        'researches',
        'article',
        'trainings',
        'day',
        'arrival',
        'leave',
        'location',
        'shift',
    ];
}
