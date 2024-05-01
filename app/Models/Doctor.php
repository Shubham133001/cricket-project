<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phonenumber',
        'profilephoto',
        'gender',
        'age',
        'address',
        'experience',
        'about',
        'city',
        'created_by',
        'specialization_id',
    ];

    public function specialization()
    {
        return $this->hasOne(Specialization::class, 'id', 'specialization_id');
    }

    public function createdby()
    {
        return $this->hasOne(Admin::class, 'id', 'created_by');
    }

    public function getCreatedAtAttribute($date)
    {
        if($date != "")
        {
            return date('d-m-Y h:i A', strtotime($date));
        }
        else
        {
            return "Null";
        }
    }

    public function getUpdatedAtAttribute($date)
    {
        if($date != "")
        {
            return date('d-m-Y h:i A', strtotime($date));
        }
        else
        {
            return "Null";
        }
    }

    
}
