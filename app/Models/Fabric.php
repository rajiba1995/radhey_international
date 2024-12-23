<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fabric extends Model
{
    protected $fillable = ['title', 'hexacode','product_id', 'status'];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }
}
