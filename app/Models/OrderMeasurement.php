<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id  ',
        'measurement_name ',
        'measurement_value ',
    ];
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function measurements()
    {
        return $this->hasMany(OrderMeasurement::class);


    }

    public function measurement()
    {
        // Ensure the foreign key and local key are correct
        return $this->belongsTo(Measurement::class, 'measurement_name', 'title');
    }
}
