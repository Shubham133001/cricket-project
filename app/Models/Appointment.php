<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'booking_date',
        'time_slot',
        'status',
        'created_by',
        'updated_by',
        'payment_payment_id',
        'invoice_id',
        'type',
        'walkin_category',
        'another_category'
    ];

    // relation with doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function another_appointment()
    {
        return $this->belongsTo(AnotherAppointment::class, 'another_category');
    }
    

    public function created_by()
    {
        // match user id with created_by
        return $this->belongsTo(Admin::class, 'created_by');
    }

    // relation with patient

    public function patient()
    {
        return $this->belongsTo(Patient::class);
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
    

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }   

    public function gettimeSlotAttribute( $value ) {
        $value = explode('-', $value);
        $starttime = date('h:i A', strtotime(trim($value[0])));
        $endtime = date('h:i A', strtotime(trim($value[1])));
        return  $starttime;
    }
}
