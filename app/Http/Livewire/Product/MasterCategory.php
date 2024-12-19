<?php

namespace App\Http\Livewire\Product;

use Livewire\WithPagination;
use App\Models\Category;
use Livewire\Component;
use App\Helpers\Helper;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class MasterCategory extends Component
{
    use WithFileUploads, WithPagination;

    public $title, $status = 1, $categoryId, $image, $search = '';
    // public $existingImage;
    protected $rules = [
        'title' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
    ];

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255|unique:categories,title,NULL,id,deleted_at,NULL',
        ]);

        $absoluteAssetPath = $this->image ? 'storage/' . $this->image->store('category_image', 'public') : null;

        Category::create([
            'title' => $this->title,
            'status' => $this->status,
            'image' => $absoluteAssetPath,
        ]);

        session()->flash('message', 'Category created successfully!');
        $this->resetFields();
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryId = $category->id;
        $this->title = $category->title;
        $this->status = $category->status;
        $this->image = $category->image;
    }

    public function update()
    {
       
        $this->validate([
            'title' => 'required|string|max:255|unique:categories,title,' . $this->categoryId . ',id,deleted_at,NULL',
        ]);
        DB::beginTransaction(); // Start the transaction
        try {
            // Find the category
            $category = Category::findOrFail($this->categoryId);

            // Determine the image path
            $imagePath = $this->image ? Helper::uploadImage($this->image, 'category') : null;
            // Update the category
            $category->update([
                'title' => $this->title,
                'status' => $this->status,
                'image' => $imagePath,
            ]);

            DB::commit(); // Commit the transaction
            session()->flash('message', 'Category updated successfully!');
            $this->resetFields();
        } catch (\Throwable $e) {
            DB::rollBack(); // Rollback the transaction in case of error

            // Flash an error message to the session or return a response
            session()->flash('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        session()->flash('message', 'Category deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);
        $category->status = !$category->status;
        $category->save();

        session()->flash('message', 'Category status updated successfully!');
    }

    public function resetFields()
    {
        $this->title = '';
        $this->status = 1;
        $this->categoryId = null;
        $this->image = null;
    }

    public function render()
    {
        $categories = Category::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->orderBy('title', 'ASC')
            ->paginate(5);

        return view('livewire.product.master-category', compact('categories'));
    }

}
