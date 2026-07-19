<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullName',
        'whatsapp',
        'case_summary',
        'date',
        'passport',
        'message',
    ];
}
