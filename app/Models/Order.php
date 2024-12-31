<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;


    protected $table = 'orders';

    protected $fillable = [
        'order_id ',
        'product_id ',
        'collection ',
        'fabrics ',
        'category ',
        'sub_category ',
        'product_name ',
        'price ',
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

}
