<?php

namespace App\Http\Livewire\Stock;

use Livewire\Component;
use App\Models\StockProduct;
use App\Models\StockFabric;
use Livewire\WithPagination;


class StockIndex extends Component
{
    use WithPagination;

    public $activeTab = 'product'; // Default active tab
    protected $paginationTheme = 'bootstrap';
    
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $products = StockProduct::with('product')->paginate(10);
        $fabrics = StockFabric::with('fabric')->get();

        return view('livewire.stock.stock-index', [
            'products' => $products,
            'fabrics' => $fabrics,
        ]);
    }
}
