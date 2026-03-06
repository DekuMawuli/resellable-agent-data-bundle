<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Http\Customs\CustomHelper;
use Illuminate\Support\Facades\DB;

class ProductComponent extends Component
{
    public $updateMode = false;
    public $allProducts = [];
    public $allCategories = [];

    public $retailPrice = "";

    public $name = "";
    public $categoryId = "";

    public $outOfStock = false;

    public $selectedProduct = null;

    // public Product $newProduct;

    protected $rules = [
        "retailPrice" => "required|numeric",
        "name" => "required|string|max:255",
        "categoryId" => "required|exists:categories,id",
        "outOfStock" => "boolean",
    ];


 public function saveProduct()
    {
        $this->validate();

        if (!$this->updateMode){

            Product::create([
                "code" => "PROD-".substr(uniqid(),5, 10),
                "name" => $this->name,
                "category_id" => $this->categoryId,
                "retail_price" => $this->retailPrice,
                "agent_price" => $this->retailPrice,
                "out_to_stock" => $this->outOfStock,
            ]);
            CustomHelper::message("success", "Product Added Successfully");
        }else{
            $this->selectedProduct->update([
                "name" => $this->name,
                "category_id" => $this->categoryId,
                "retail_price" => $this->retailPrice,
                "agent_price" => $this->retailPrice,
                "out_to_stock" => $this->outOfStock,
            ]);
            CustomHelper::message("info", "Product Updated Successfully");
            $this->updateMode = false;
        }
        $this->initVars();
        $this->fetchProducts();
    }

    public function clearSelection(){
        $this->initVars();
        $this->updateMode = false;
    }



    public function render()
    {


        return view('livewire.admin.product-component');
    }

    public function setForEdit($code)
    {

        // dd($this->newProduct);
        $this->selectedProduct = Product::firstWhere("code", "=", $code);
        $this->retailPrice = $this->selectedProduct->agent_price;
        $this->name = $this->selectedProduct->name;
        $this->categoryId = $this->selectedProduct->category_id;
        $this->outOfStock = $this->selectedProduct->out_to_stock;

        $this->updateMode = true;
    }

    public function toggleStockStatus($code)
    {
       $package = Product::firstWhere("code", "=", $code);
       $package->out_to_stock = !$package->out_to_stock;
       $package->save();
        session()->flash("at", "warning");
        if ($package->out_to_stock){
            session()->flash("am", "Product Status: Out of Stock");
        }else{
            session()->flash("am", "Product Status: In Stock");
        }
        $this->initVars();
        $this->fetchProducts();
    }

    public function deleteProduct($code)
    {
        Product::where("code", "=", $code)
            ->delete();
        session()->flash("at", "warning");
        session()->flash("am", "Product Removed Successfully");
        $this->initVars();
        $this->fetchProducts();
    }


    private function fetchProducts()
    {
        $this->allProducts = Product::query()
         ->with("category")
         ->orderBy("category_id", "asc")

         ->get();


         $this->allCategories = Category::all();
    }
    private function initVars()
    {
        $this->updateMode = false;
        $this->retailPrice = "";
        $this->name = "";
        $this->categoryId = "";
        $this->outOfStock = false;

    }

    public function mount()
    {
        $this->fetchProducts();
        $this->initVars();
        $this->allCategories = Category::all();
    }
}
