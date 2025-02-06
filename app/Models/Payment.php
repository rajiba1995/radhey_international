<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;


    protected $table = "payments";

    protected $fillable = [
        'order_id', 'paid_amount','user_id', 'admin_id', 'supplier_id', 'expense_id', 'service_slip_id',
        'discount_id', 'payment_for', 'payment_in', 'bank_cash', 'voucher_no', 'payment_date',
        'payment_mode', 'amount', 'chq_utr_no', 'bank_name', 'narration', 'created_from',
        'is_gst', 'created_by', 'updated_by'
    ];

    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}
