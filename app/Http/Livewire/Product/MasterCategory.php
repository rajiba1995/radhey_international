<?php

namespace App\Http\Livewire\Product;

use Livewire\WithPagination;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;

class MasterCategory extends Component
{
    use WithFileUploads, WithPagination;

    public $title, $status = 1, $categoryId, $image, $search = '';
    // public $existingImage;
    protected $rules = [
        'title' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
    ];

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

        $category = Category::findOrFail($this->categoryId);
        // $absoluteAssetPath = $this->image instanceof \Livewire\TemporaryUploadedFile
        //     ? 'storage/' . $this->image->store('category_image', 'public')
        //     : $category->image;

        if ($this->image) {
            $filePath = $this->gst_file->store('category_image','public');
                $absoluteAssetPath = 'storage/' . $filePath;
        }

        $category->update([
            'title' => $this->title,
            'status' => $this->status,
            'image' => $absoluteAssetPath,
        ]);

        session()->flash('message', 'Category updated successfully!');
        $this->resetFields();
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

    public function getImagePreviewProperty()
    {
        return $this->image instanceof \Livewire\TemporaryUploadedFile
            ? $this->image->temporaryUrl()
            : ($this->image ? asset($this->image) : null);
    }
}
