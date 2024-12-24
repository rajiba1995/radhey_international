<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function ctype()
    {
        return $this->belongsTo(CollectionType::class, 'collection_type');
    }

    public function measurements()
    {
        return $this->hasMany(OrderMeasurement::class);
    }

}
