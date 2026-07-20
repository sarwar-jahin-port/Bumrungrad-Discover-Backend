<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirAmbulanceHub extends Model
{
    use HasFactory;

    protected $fillable = [
        'city',
        'office_name',
        'building',
        'floor_map',
        'address',
        'phone1',
        'phone2',
        'whatsapp_hotline',
        'operational_hours',
        'map_embed_url',
    ];
}
