<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fabric extends Model
{
    protected $table = "fabrics";
    protected $fillable = ['title', 'image','product_id','threshold_price' ,'status'];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }
}
