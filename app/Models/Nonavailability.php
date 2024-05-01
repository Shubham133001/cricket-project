<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nonavailability extends Model
{
    use HasFactory;
    protected $fillable = [
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'event_name',
    ];
}
