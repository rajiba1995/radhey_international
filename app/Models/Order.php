<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;


    protected $table = 'orders';

    protected $fillable = [
        'customer_id',
        'business_type',
        'order_number',
        'customer_name',
        'customer_email',
        'billing_address',
        'shipping_address',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'last_payment_date',
        'payment_mode',
        'status',
        'business_type',
        'created_by' 
    ];
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function measurements()
    {
        return $this->hasMany(OrderMeasurement::class);
    }
    
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
  
    
    public function businessType()
    {
        return $this->belongsTo(BusinessType::class, 'business_type');
    }
    
}
