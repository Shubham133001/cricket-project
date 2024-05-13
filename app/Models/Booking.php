<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'slot_id',
        'date',
        'status',
        'payment_status',
        'category_id',
        'team_id',
        'invoice_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function cancellation_request()
    {
        return $this->belongsTo(CancellationRequest::class,'id','booking_id');
    }
}
