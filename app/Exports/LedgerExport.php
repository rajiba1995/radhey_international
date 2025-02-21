<?php

namespace App\Exports;

use App\Models\Ledger;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class LedgerExport implements FromQuery, WithHeadings, WithMapping
{
    public $from_date, $to_date, $user_type, $staff_id, $customer_id, $supplier_id, $bank_cash, $search;

    public function __construct($from_date = null, $to_date = null, $user_type = null, $staff_id = null, $customer_id = null, $supplier_id = null, $bank_cash = null, $search = null)
    {
        // Assigning the parameters to class properties
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->user_type = $user_type;
        $this->staff_id = $staff_id;
        $this->customer_id = $customer_id;
        $this->supplier_id = $supplier_id;
        $this->bank_cash = $bank_cash;
        $this->search = $search;  // Make sure search is assigned
    }

    public function query()
    {
        return Ledger::query()
            // Apply 'search' filter if provided
            ->when($this->search, function ($query) {
                $query->where('transaction_id', 'like', '%' . $this->search . '%')
                    ->orWhere('purpose', 'like', '%' . $this->search . '%')
                    ->orWhereHas('staff', function($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('customer', function($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('supplier', function($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            // Apply other filters dynamically
            ->when($this->from_date, function ($query) {
                $query->whereDate('entry_date', '>=', $this->from_date);
            })
            ->when($this->to_date, function ($query) {
                $query->whereDate('entry_date', '<=', $this->to_date);
            })
            ->when($this->user_type, function ($query) {
                if ($this->user_type === 'staff' && $this->staff_id) {
                    $query->where('staff_id', $this->staff_id);
                } elseif ($this->user_type === 'customer' && $this->customer_id) {
                    $query->where('customer_id', $this->customer_id);
                } elseif ($this->user_type === 'supplier' && $this->supplier_id) {
                    $query->where('supplier_id', $this->supplier_id);
                }
            })
            ->when($this->bank_cash, function ($query) {
                $query->where('bank_cash', $this->bank_cash);
            })
            // Apply sorting
            ->with(['staff', 'customer', 'supplier'])
            ->orderBy('entry_date', 'desc');
    }

    public function headings(): array
    {
        return [
            'ID', 'User Type', 'Staff Name', 'Customer Name', 'Supplier Name',
            'Transaction ID', 'Amount', 'Debit', 'Credit', 'Bank/Cash',
            'Entry Date', 'Purpose', 'Date', 'Updated Date'
        ];
    }

    public function map($ledger): array
    {
        return [
            $ledger->id,
            ucfirst($ledger->user_type),
            $ledger->staff ? $ledger->staff->name : '',
            $ledger->customer ? $ledger->customer->name : '',
            $ledger->supplier ? $ledger->supplier->name : '',
            $ledger->transaction_id ?? '',
            number_format($ledger->transaction_amount, 2),
            $ledger->is_debit ? number_format($ledger->transaction_amount, 2) : '',
            $ledger->is_credit ? number_format($ledger->transaction_amount, 2) : '',
            ucfirst($ledger->bank_cash),
            $ledger->entry_date ? Carbon::parse($ledger->entry_date)->format('d-m-Y') : '',
            // $ledger->purpose ?? 'N/A',
            ucwords(str_replace('_', ' ', $ledger->purpose))?? '',
            $ledger->created_at ? Carbon::parse($ledger->created_at)->format('d-m-Y H:i:s') : '',
            $ledger->updated_at ? Carbon::parse($ledger->updated_at)->format('d-m-Y H:i:s') : ''
        ];
    }
}
