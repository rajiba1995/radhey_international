<?php

namespace App\Http\Livewire\Product;

use Livewire\WithPagination;
use App\Models\Category;
use Livewire\Component;

class MasterCategory extends Component
{
    use WithPagination;

    public $title, $status = 1, $categoryId;
    public $search = '';

    protected $rules = [
        'title' => 'required|string|max:255',
    ];

    public function render()
    {
        $categories = Category::query()
            ->where('title', 'like', '%' . $this->search . '%')
            ->orderBy('title','ASC')
            ->paginate(5);

        return view('livewire.product.master-category', compact('categories'));
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255|unique:categories,title,NULL,id,deleted_at,NULL',
        ]);

        Category::create([
            'title' => $this->title,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Category created successfully!');
        // Get the last URL from session (if it exists)
        $redirectUrl = session('redirect_url', route('admin.categories')); // Default redirect if not set

        // Redirect to the last URL or fallback to subcategories route
        session()->forget('redirect_url');
        return redirect($redirectUrl);
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryId = $category->id;
        $this->title = $category->title;
        $this->status = $category->status;
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|string|max:255|unique:categories,title,' . $this->categoryId . ',id,deleted_at,NULL',
        ]);

        $category = Category::findOrFail($this->categoryId);
        $category->update([
            'title' => $this->title,
            'status' => $this->status,
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
        $category->status = !$category->status;  // Toggle the status
        $category->save();  // Save the updated status

        session()->flash('message', 'Category status updated successfully!');
    }

    public function resetFields()
    {
        $this->title = '';
        $this->status = 1;
        $this->categoryId = null;
    }
}
