<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class PublicProductsListComponent extends Component
{
    public string $search = "";

    public string $categoryCode = "all";

    public string $stockFilter = "all";

    public function mount(?string $initialCategoryCode = "all"): void
    {
        $initialCategoryCode = strtolower((string) $initialCategoryCode);
        $this->categoryCode = $initialCategoryCode !== "" ? $initialCategoryCode : "all";
    }

    public function render()
    {
        $categories = Category::query()
            ->where("is_active", true)
            ->orderBy("name")
            ->get();

        $products = Product::query()
            ->with("category")
            ->when($this->search !== "", function ($query): void {
                $term = trim($this->search);
                $query->where(function ($subQuery) use ($term): void {
                    $subQuery
                        ->where("name", "like", "%{$term}%")
                        ->orWhereHas("category", function ($categoryQuery) use ($term): void {
                            $categoryQuery->where("name", "like", "%{$term}%");
                        });
                });
            })
            ->when($this->categoryCode !== "all" && $this->categoryCode !== "", function ($query): void {
                $categoryCode = $this->categoryCode;
                $query->whereHas("category", function ($categoryQuery) use ($categoryCode): void {
                    $categoryQuery->where("code", $categoryCode);
                });
            })
            ->when($this->stockFilter === "in_stock", function ($query): void {
                $query->where("out_to_stock", false);
            })
            ->when($this->stockFilter === "out_of_stock", function ($query): void {
                $query->where("out_to_stock", true);
            })
            ->orderBy("category_id")
            ->orderBy("name")
            ->get();

        return view("livewire.public-products-list-component", [
            "categories" => $categories,
            "products" => $products,
        ]);
    }
}
