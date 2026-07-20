<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'cover_photo', 'location', 'description', 'content',
        'informations', 'conditions', 'treatments',
        'floor_map', 'operational_hours', 'whatsapp_hotline',
    ];
}
