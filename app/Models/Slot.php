<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'days', 'start_time', 'end_time', 'start_date', 'end_date',  'status', 'price', 'advanceprice', 'bookings_allowed', 'title'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    // comvert time to 12 hour format
    public function getStartTimeAttribute($value)
    {
        return date('h:i A', strtotime($value));
    }
    public function getEndTimeAttribute($value)
    {
        return date('h:i A', strtotime($value));
    }
    
}
