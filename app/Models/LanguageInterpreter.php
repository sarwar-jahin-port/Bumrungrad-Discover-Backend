<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageInterpreter extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullName',
        'whatsapp',
        'concern',
    ];
}
