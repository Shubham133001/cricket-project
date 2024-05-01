<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phonenumber',
        'gender',
        'age',
        'address',
        'city',
        'created_by',
        'walkin_category',
        'another_name'
    ];


    public function admins()
    {
        return $this->hasMany(Admin::class, 'id', 'created_by');
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
