<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_id',
        'slot_id',
        'amount',
        'currency',
        'status',
        'method',
        'user_id'
    ];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'payment_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_phonenumber', 'phonenumber');
    }
    public function getCreatedAtAttribute($date)
    {
        if ($date != "") {
            return date('d-m-Y h:i A', strtotime($date));
        } else {
            return "Null";
        }
    }
}
