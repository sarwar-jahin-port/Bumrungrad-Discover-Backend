<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctorappoinment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialty',
        'subSpecialty',
        'package',
        'doctor',
        'medicalDesc',
        'selectedDate',
        'selectedDate2',
        'shift',
        'shift2',
        'HnNumber',
        'PataientFirstName',
        'PataientLastName',
        'PataientCitizenship',
        'PataientGender',
        'PataientEmail',
        'PataientPhone',
        'PataientDob',
        'RequestorFirstname',
        'RequestorLastName',
        'RequestorEmail',
        'RequestorPhone',
        'RequestoerRelation',
        'mediicalCorncern',
        'country',
        'countryOfResidence',
        'passport',
        'medicalReport1',
        'medicalReport2',
        'medicalReport3',
        'oldPataint',
        'firstSiftTime',
        'SecondSiftTime',
        'driveLink1',
        'driveLink2',
        'status',
    ];
}
