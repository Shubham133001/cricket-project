<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnotherAppointment extends Model
{
    use HasFactory;

    public $table = 'another_appointment';
    
    protected $fillable = [
        'another_name',
        'price',
        'created_at',
        'updated_at',

    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'another_category');
    }
    
}
