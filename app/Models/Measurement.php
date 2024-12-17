<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    protected $fillable = ['subcategory_id', 'title', 'short_code', 'status', 'position'];

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
}