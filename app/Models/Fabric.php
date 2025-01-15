<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fabric extends Model
{
    protected $table = "fabrics";
    protected $fillable = ['title', 'image','threshold_price' ,'status'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_fabrics', 'fabric_id', 'product_id');
    }
    
}
