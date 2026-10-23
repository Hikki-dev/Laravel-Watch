<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class ProductSearch extends Component
{
    public $search = '';

    public function render()
    {
        $products = [];
        
        if (strlen($this->search) >= 2) {
            $products = Product::where('is_active', true)
                ->where(fn ($q) => $q->whereLike('name', '%' . $this->search . '%')
                    ->orWhereLike('brand', '%' . $this->search . '%'))
                ->take(5)
                ->get();
        }

        return view('livewire.product-search', [
            'results' => $products
        ]);
    }
}
