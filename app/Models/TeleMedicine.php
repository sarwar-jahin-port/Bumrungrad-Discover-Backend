<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeleMedicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullName',
        'patientType',
        'hnNum',
        'birthDate',
        'passportId',
        'nationality',
        'residence',
        'preferredDate',
        'preferredDoctor',
        'timeSlot',
        'purposeAppointment',
        'investigationDocument',
        'contactDetails',
        'paymentType',
        'epaymentlink',
        'interpreter',
        'specificConcern',
        'created_at',
        'updated_at',
    ];
}
