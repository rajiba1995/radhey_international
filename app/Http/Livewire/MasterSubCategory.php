<?php

namespace App\Http\Livewire;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\SubCategory;
use App\Models\Category;

class MasterSubCategory extends Component
{
    use WithPagination;

    public $categories; // Holds categories for dropdown
    public $category_id, $title, $status = 1, $subCategoryId;
    public $search = '';

    protected $rules = [
        'category_id' => 'required|exists:categories,id',
        'title' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->categories = Category::all(); // Load categories for dropdown
    }

    public function render()
    {
        $subcategories = SubCategory::with('category')
            ->where('title', 'like', '%' . $this->search . '%')
            ->paginate(10);
        return view('livewire.master-sub-category',compact('subcategories'));
    }

    public function store(){
        $this->validate();
        SubCategory::create([
            'category_id' => $this->category_id,
            'title' => $this->title,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Subcategory created successfully!');
        $this->resetFields();

    }

    public function edit($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $this->subCategoryId = $subcategory->id;
        $this->category_id = $subcategory->category_id;
        $this->title = $subcategory->title;
        $this->status = $subcategory->status;
    }

    public function update()
    {
        $this->validate();

        $subcategory = SubCategory::findOrFail($this->subCategoryId);
        $subcategory->update([
            'category_id' => $this->category_id,
            'title' => $this->title,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Subcategory updated successfully!');
        $this->resetFields();
    }

    public function destroy($id)
    {
        SubCategory::findOrFail($id)->delete();
        session()->flash('message', 'Subcategory deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $category = SubCategory::findOrFail($id);
        $category->status = !$category->status;  // Toggle the status
        $category->save();  // Save the updated status

        session()->flash('message', 'SubCategory status updated successfully!');
    }

    public function resetFields()
    {
        $this->category_id = '';
        $this->title = '';
        $this->status = 1;
        $this->subCategoryId = null;
    }



}