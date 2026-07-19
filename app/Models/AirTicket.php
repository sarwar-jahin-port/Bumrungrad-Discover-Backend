<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullName',
        'whatsapp',
        'booking_date',
        'country',
        'doc',
        'created_at',
        'updated_at',
    ];
}
