<?php

namespace App\Livewire\Admin;

use App\Http\Customs\CustomHelper;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class CategoryComponent extends Component
{


    public Category $newCategory;
    public $updateMode = false;
    public $allCategories = [];

    protected $rules = [
        "newCategory.name" => "required|string",
    ];



    public function saveCategory(){

        if (!$this->updateMode){

            $this->validate();

            $this->newCategory->code = Str::uuid();
            $this->newCategory->save();
            session()->flash("at", "success");
            session()->flash("am", "Category Added Successfully");

        }else{
            $this->validate([

                "newCategory.name" => "required",
            ]);

            $this->newCategory->save();
            session()->flash("at", "info");
            session()->flash("am", "Category Updated Successfully");
            $this->updateMode = false;
        }

        $this->initVars();
        $this->fetchCategories();
    }


    public function deleteCat($code)
    {
        Category::where("code", "=", $code)
            ->delete();
        CustomHelper::message("info", "Category Removed");
        $this->fetchCategories();
    }

    public function setForEdit($code){
        $this->newCategory = Category::firstWhere("code", "=", $code);
        $this->updateMode = true;
    }

    public function clearSelection(){
        $this->initVars();
        $this->updateMode = false;
}



    public function render()
    {
        return view('livewire.admin.category-component');
    }

    private function initVars(){
        $this->newCategory = new Category();
        $this->newCategory->is_active = true;

    }

    private function fetchCategories(){
        $this->allCategories = Category::where("is_active", true)->get();
    }

    public function mount(){
        $this->fetchCategories();
        $this->initVars();
    }
}
