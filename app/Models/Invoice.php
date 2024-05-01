<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'slot_id',
        'status',
        'amount',
        'firstpayment',
        'description',
        'created_by',
        'payment_id',
        'user_id',
        'gateway'
    ];
    // public function appointment()
    // {
    //     return $this->hasOne(Appointment::class);
    // }

    public function created_by()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }
    public function getCreatedAtAttribute($date)
    {
        if ($date != "") {
            return date('d-m-Y h:i A', strtotime($date));
        } else {
            return "Null";
        }
    }
    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }
}
