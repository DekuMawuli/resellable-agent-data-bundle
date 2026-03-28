<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Services\RealestApiService;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Http\Customs\CustomHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    /** Products fetched from the Realest catalog for the selected network */
    public array $catalogProducts = [];

    /** 'idle' | 'loaded' | 'empty' | 'error' | 'unconfigured' */
    public string $catalogStatus = 'idle';

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

    /**
     * Fires when the admin changes the network/category select.
     * Fetches (or hits cache for) the full Realest product catalog,
     * then filters it down to the chosen network.
     */
    public function updatedCategoryId(): void
    {
        $this->catalogProducts = [];
        $this->catalogStatus   = 'idle';

        if (!$this->categoryId) {
            return;
        }

        $category = Category::find($this->categoryId);
        if (!$category) {
            return;
        }

        try {
            // Cache the full catalog for 1 hour — one real HTTP call per hour at most.
            $catalog = Cache::remember('realest_catalog', 3600, function () {
                return app(RealestApiService::class)->getProducts();
            });

            if (($catalog['status'] ?? '') !== 'success') {
                // Bust cache so a stale error response doesn't persist
                Cache::forget('realest_catalog');
                $this->catalogStatus = isset($catalog['message']) && str_contains(strtolower($catalog['message']), 'not configured')
                    ? 'unconfigured'
                    : 'error';
                return;
            }

            $networks = $catalog['data']['networks'] ?? [];

            // Match by network name case-insensitively
            $matched = collect($networks)
                ->first(fn ($n) => strtolower($n['network'] ?? '') === strtolower($category->name));

            if (!$matched || empty($matched['products'])) {
                $this->catalogStatus = 'empty';
                return;
            }

            $this->catalogProducts = $matched['products'];
            $this->catalogStatus   = 'loaded';

        } catch (\Throwable $e) {
            Log::channel('realest')->error('Catalog fetch failed in ProductComponent', [
                'message' => $e->getMessage(),
            ]);
            Cache::forget('realest_catalog');
            $this->catalogStatus = 'error';
        }
    }

    /**
     * Pre-fills the bundle size field from a catalog item the admin clicked.
     */
    public function fillFromCatalog(string $name): void
    {
        $this->name = $name;
    }



    public function render()
    {


        return view('livewire.admin.product-component');
    }

    public function setForEdit($code)
    {
        $this->selectedProduct = Product::firstWhere("code", "=", $code);
        $this->retailPrice     = $this->selectedProduct->agent_price;
        $this->name            = $this->selectedProduct->name;
        $this->categoryId      = $this->selectedProduct->category_id;
        $this->outOfStock      = $this->selectedProduct->out_to_stock;
        $this->updateMode      = true;

        // Load the catalog for the existing product's network so the admin
        // can see costs and pick a different size if needed.
        $this->updatedCategoryId();
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
        $this->updateMode      = false;
        $this->retailPrice     = "";
        $this->name            = "";
        $this->categoryId      = "";
        $this->outOfStock      = false;
        $this->catalogProducts = [];
        $this->catalogStatus   = 'idle';
    }

    public function mount()
    {
        $this->fetchProducts();
        $this->initVars();
        $this->allCategories = Category::all();
    }
}
