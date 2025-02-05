<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'purpose',
        'purpose_description',
        'order_id',
        'user_id',
        'transaction_date',
        'transaction_type',
        'payment_method',
        'paid_amount',
        'remaining_amount',
        'remarks',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
