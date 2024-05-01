<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'admin_group_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function admingroup()
    {
        return $this->hasOne(AdminGroup::class, 'id', 'admin_group_id');
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

    public function getGuardNames()
    {
        return $this->guard;
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
