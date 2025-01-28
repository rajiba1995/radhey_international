<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalogue extends Model
{
    protected $table = "catalogue";
    protected $fillable = [
        'catalogue_title_id',
        'page_number',
        'image'
    ];
    
}
