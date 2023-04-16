<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'enrollment_id',
        'payment_method',
        'payment_schedule',
        'registration_is_paid',
    ];

    protected $casts = [
        'registration_is_paid' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

     public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
    // ...
    public function paymentHistories()
    {
        return $this->hasMany(PaymentHistory::class);
    }

}
