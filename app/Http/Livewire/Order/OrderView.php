<?php

namespace App\Http\Livewire\Order;
use App\Models\Order;
use \App\Models\Product;
use Livewire\Component;

class OrderView extends Component
{
    public $oderId;

    public function mount($id){
        $this->orderId = $id;
    }

    public function render()
    {
         // Fetch the order and its related items
         $order = Order::with('items')->findOrFail($this->orderId);
         // Fetch product details for each order item
        $orderItems = $order->items->map(function ($item) {
            $product = Product::find($item->product_id);
            return [
                'product_name' => $item->product_name ?? $product->name,
                'price' => $item->price ,
                // 'quantity' => $item->quantity,
                'product_image' => $product ? $product->product_image : null,
            ];
        });
        return view('livewire.order.order-view',[
            'order' => $order,
            'orderItems' => $orderItems
        ]);
    }
}
