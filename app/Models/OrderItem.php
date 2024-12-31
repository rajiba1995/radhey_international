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
    public function colection()
    {
        return $this->belongsTo(Collection::class);
    }
   

    public function measurements()
    {
        return $this->hasMany(OrderMeasurement::class);
    }

    public function collection()
{
    return $this->belongsTo(Collection::class, 'collection');
}

public function category()
{
    return $this->belongsTo(Category::class, 'category');
}


}
