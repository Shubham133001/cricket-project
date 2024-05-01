<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blockappslots extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'bookingdate',
        'timeslots',
        'note',
        'created_at',
        'updated_at',
    ];
}