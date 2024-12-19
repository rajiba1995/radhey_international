<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = "products";

    protected $fillable = [
        'category_id',
        'sub_category_id',
        'name',
        'product_code',
        'short_description',
        'description',
        'gst_details',
        'product_image',
    ];

    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }

    public function sub_category(){
        return $this->belongsTo(SubCategory::class,'sub_category_id');
    }

    
    
}
