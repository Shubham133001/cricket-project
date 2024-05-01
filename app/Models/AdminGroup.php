<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function admins()
    {
        return $this->hasMany(Admin::class, 'admin_group_id', 'id');
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

    public function permissions()
    {
        return $this->hasMany(AdminPermission::class, 'admin_group_id', 'id');
    }
}
