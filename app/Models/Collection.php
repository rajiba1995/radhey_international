<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'short_code', 'collection_type'];

    public function type()
    {
        return $this->belongsTo(CollectionType::class,'collection_type');
    }
}
