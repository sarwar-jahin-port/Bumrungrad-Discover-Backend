<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id', 'title', 'slug', 'description', 'price', 'location', 'content',
        'shift1', 'shift2', 'conditions', 'inclusions', 'exclusions', 'cover_photo',
    ];
}
