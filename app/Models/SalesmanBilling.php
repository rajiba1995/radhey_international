<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesmanBilling extends Model
{   
    protected $table = 'salesman_billing_number';
    protected $fillable = ['salesman_id','start_no','end_no','status'];

    public function salesman()
    {
        return $this->belongsTo(User::class, 'salesman_id');
    }
}
