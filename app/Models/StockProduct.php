<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockProduct extends Model
{

    protected $table = 'stock_products';
    protected $fillable = [
        'stock_id',
        'product_id',
        'qty_in_pieces',
        'piece_price',
        'total_price',
    ];

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
}
