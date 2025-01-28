<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Catalogue;
use App\Models\CatalogueTitle;

class MasterCatalogue extends Component
{
    use WithPagination,WithFileUploads;
    public $catalogueId;
    public $search;
    public $catalogue_title_id;
    public $page_number;
    public $image;
    public $per_page = 10;
    public $catalogueTitle;

    protected $rules = [
        'catalogue_title_id'=>'required|string|max:255',
        'page_number'=> 'required|numeric',
        'image'=>'required|mimes:jpg,jpeg,png,gif,svg|max:204',
    ];  

    public function mount(){
        $this->catalogueTitle = CatalogueTitle::all();
    }

    public function storeCatalogue(){
        $this->validate();
        
    }
    public function render()
    {
        return view('livewire.product.master-catalogue');
    }
}
