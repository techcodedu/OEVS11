<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'amount',
        'date_paid',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
