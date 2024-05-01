<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'slot_id',
        'quantity',
        'price',
        'total',
        'description',
        'status'
    ];

    public function slots()
    {
        return $this->belongsTo(Slot::class, 'slot_id');
    }
}
