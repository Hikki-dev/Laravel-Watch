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
            $products = Product::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('brand', 'like', '%' . $this->search . '%')
                ->where('is_active', true)
                ->take(5)
                ->get();
        }

        return view('livewire.product-search', [
            'results' => $products
        ]);
    }
}
