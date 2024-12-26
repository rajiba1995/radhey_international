<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use App\Models\Collection;
use App\Models\CollectionType;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CollectionIndex extends Component
{
    use WithPagination;

    public $collectionsType;
    public $short_code, $title, $collectionId, $collection_type;
    public $status = 1;
    public $search = '';

    public function mount()
    {
        $this->collectionsType = CollectionType::orderBy('title', 'ASC')->get();
    }

    public function store()
    {
        $this->validate([
            'collection_type' => ['required'],
            'title' => [
                'required',
                'string',
                'max:255',
                'unique:collections,title',
            ],
            'short_code' => [
                'nullable',
                'string',
                'max:255',
                'unique:collections,short_code',
            ],
        ]);

        Collection::create([
            'title' => ucfirst($this->title),
            'short_code' => $this->short_code,
            'collection_type' => $this->collection_type,
        ]);

        $this->resetForm();

        session()->flash('message', 'Collection created successfully!');
    }

    public function edit($id)
    {
        $collection = Collection::findOrFail($id);
        $this->collectionId = $collection->id;
        $this->title = $collection->title;
        $this->short_code = $collection->short_code;
        $this->collection_type = $collection->collection_type;
    }

    public function update()
    {
        $this->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('collections', 'title')->ignore($this->collectionId),
            ],
            'short_code' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('collections', 'short_code')->ignore($this->collectionId),
            ],
        ]);

        $collection = Collection::findOrFail($this->collectionId);
        $collection->update([
            'title' => ucfirst($this->title),
            'short_code' => $this->short_code,
            'collection_type' => $this->collection_type,
        ]);

        $this->resetForm();

        session()->flash('message', 'Collection updated successfully!');
    }

    public function destroy($id)
    {
        Collection::findOrFail($id)->delete();
        session()->flash('message', 'Collection deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $collection = Collection::findOrFail($id);
        $collection->update(['status' => !$collection->status]);
        session()->flash('message', 'Collection status updated successfully!');
    }

    public function resetForm()
    {
        $this->title = null;
        $this->short_code = null;
        $this->collection_type = null;
        $this->collectionId = null;
    }
    public function changeType($value){
        $this->collection_type = $value;
    }

    public function render()
    {
        $collections = Collection::where('title', 'like', "%{$this->search}%")
            ->orWhere('short_code', 'like', "%{$this->search}%")
            ->orderBy('collection_type', 'ASC')
            ->orderBy('title', 'ASC')
            ->paginate(10);

        return view('livewire.product.collection-index', [
            'collections' => $collections,
        ]);
    }
}
