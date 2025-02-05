<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMeasurement extends Model
{
    use HasFactory;


    protected $table = 'order_measurements';

    protected $fillable = [
        'order_item_id',
        'measurement_name',
        'measurement_value',
    ];
   
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

}
