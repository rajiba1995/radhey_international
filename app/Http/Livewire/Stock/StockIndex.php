<?php

namespace App\Http\Livewire\Stock;

use Livewire\Component;
use App\Models\StockProduct;
use App\Models\StockFabric;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockProductExport;
use Carbon\Carbon;
use App\Exports\StockFabricExport;


class StockIndex extends Component
{
    use WithPagination;

    public $activeTab = 'product'; // Default active tab
    protected $paginationTheme = 'bootstrap';
    public $startDate;
    public $endDate;
    public $search = '';
    
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    // public function exportStockProduct()
    // {
    //     return Excel::download(new StockProductExport($this->startDate, $this->endDate), 'stock_products.xlsx');
    // }


    public function exportStockProduct()
    {
        $fileName = 'product_stock_' . now()->format('Ymd_His') . '.csv';

        return Excel::download(new StockProductExport($this->search, $this->startDate, $this->endDate), $fileName);
    }


    // public function exportStockFabric()
    // {
    //     $fileName = 'fabric_stock_' . Carbon::now()->format('Ymd_His') . '.csv';

    //     return Excel::download(new StockFabricExport($this->startDate, $this->endDate), $fileName);
    // }
    public function exportStockFabric()
    {
        $fileName = 'fabric_stock_' . Carbon::now()->format('Ymd_His') . '.csv';
        return Excel::download(new StockFabricExport($this->search, $this->startDate, $this->endDate), $fileName);
    }

    public function render()
    {
        // $products = StockProduct::with('product')->paginate(10);

        $products = StockProduct::with('product')
        ->when($this->search, function ($q) {
            $q->whereHas('product', function ($products) {
                $products->where('name', 'like', '%' . $this->search . '%');
            });
        })
        ->when($this->startDate, function ($q) {
            $q->whereDate('created_at', '>=', $this->startDate);
        })
        ->when($this->endDate, function ($q) {
            $q->whereDate('created_at', '<=', $this->endDate);
        })
        ->paginate(10);
        // $fabrics = StockFabric::with('fabric')->get();

        $fabrics = StockFabric::with('fabric')
            ->when($this->search, function ($q) {
                $q->whereHas('fabric', function ($fabrics) {
                    $fabrics->where('title', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->startDate, function ($q) {
                $q->whereDate('created_at', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($q) {
                $q->whereDate('created_at', '<=', $this->endDate);
            })
            ->paginate(10);

        return view('livewire.stock.stock-index', [
            'products' => $products,
            'fabrics' => $fabrics,
        ]);
    }

}
