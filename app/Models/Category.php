<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes trait

class Category extends Model
{
    use HasFactory, SoftDeletes; // Enable SoftDeletes for soft deleting functionality

    // Define the table name if it doesn't follow Laravel's default pluralization convention
    protected $table = 'categories';

    // Define the fillable columns to protect against mass assignment vulnerabilities
    protected $fillable = ['title', 'status'];

    // Specify the columns that should be used for soft deletes
    protected $dates = ['deleted_at'];

    // Optionally, if you want to restrict the status to only '0' or '1'
    protected $casts = [
        'status' => 'boolean',
    ];

    public function subcategory(){
        return $this->hasmany(SubCategory::class);
    }
}