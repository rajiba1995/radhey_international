<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockFabric extends Model
{
    protected $table = 'stocks';
    protected $fillable = [
        'stock_id',
        'fabric_id',
        'qty_in_meter',
        'piece_price',
        'total_price',
    ];
}
