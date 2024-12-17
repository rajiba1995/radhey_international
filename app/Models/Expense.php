<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = "expenses";
    protected $fillable = [
        'parent_id',
        'title',
        'description',
        'for_debit',
        'for_credit',
        'for_staff',
        'for_store',
        'for_partner',
    ];
}
