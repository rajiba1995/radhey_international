<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
   
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'collection',
        'fabrics',
        'category',
        'sub_category',
        'product_name',
        'price ',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    // public function colection()
    // {
    //     return $this->belongsTo(Collection::class);
    // }
    public function collection()
    {
        return $this->belongsTo(Collection::class, 'collection', 'id');
    }

    public function measurements()
    {
        return $this->hasMany(OrderMeasurement::class);
    }

//     public function collection()
// {
//     return $this->belongsTo(Collection::class, 'collection','id');
// }

public function category()
{
    return $this->belongsTo(Category::class, 'category','id');
}
public function fabric()
    {
        return $this->belongsTo(Fabric::class, 'fabrics','title');
    }

}
