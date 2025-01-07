<?php

namespace App\Http\Livewire\Order;

use App\Models\Ledger;
use Livewire\Component;
use App\Models\Order;

class LedgerView extends Component
{
    public $orderId;
    public $transactions = [];
    public $transaction_date, $transaction_type, $paid_amount, $remaining_amount, $remarks;

    public $totalPaid, $totalRemaining;

    // The mount method is called when the component is initialized
    public function mount($id)
    {

        $this->order = Order::findOrFail($id);
        $this->orderId = $id;
        $this->loadTransactions();
        
        
    }

//     public function mount($id)
// {
//     $this->orderId = $id;

//     // Load the order using the provided ID
//     $this->order = Order::with('items.ctype', 'items.fabrics')->findOrFail($id);

//     // Load transactions for the order
//     $this->loadTransactions();

//     // Map items if needed
//     $this->order->mappedItems = $this->order->items->map(function($item) {
//         return [
//             'type' => $item->ctype->title ?? 'N/A',
//             'fabric' => $item->fabrics ?? 'N/A',
//             'product_name' => $item->product_name ?? 'N/A',
//             'price' => number_format($item->price, 2),
//         ];
//     });
// }


    public function loadTransactions()
    {
        $this->transactions = Ledger::where('order_id', $this->orderId)
            ->orderBy('transaction_date', 'DESC')
            ->get();

        $this->calculateSummary();
    }

    public function calculateSummary()
    {
        $this->totalPaid = $this->transactions->sum('paid_amount');
        $this->totalRemaining = $this->transactions->sum('remaining_amount');
    }

    public function addTransaction()
    {
        $this->validate([
            'transaction_date' => 'required|date',
            'transaction_type' => 'required|string',
            'paid_amount' => 'required|numeric|min:0',
            'remaining_amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        Ledger::create([
            'order_id' => $this->orderId,
            'transaction_date' => $this->transaction_date,
            'transaction_type' => $this->transaction_type,
            'paid_amount' => $this->paid_amount,
            'remaining_amount' => $this->remaining_amount,
            'remarks' => $this->remarks,
        ]);

        $this->resetInput();
        $this->loadTransactions();
        session()->flash('message', 'Transaction added successfully.');
    }

    public function resetInput()
    {
        $this->transaction_date = null;
        $this->transaction_type = null;
        $this->paid_amount = null;
        $this->remaining_amount = null;
        $this->remarks = null;
    }

    public function render()
    {
        return view('livewire.order.ledger-view', [
            'order' => $this->order
        ]);
    }
}


