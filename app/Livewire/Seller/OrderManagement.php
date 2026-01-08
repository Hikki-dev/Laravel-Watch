<?php

namespace App\Livewire\Seller;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class OrderManagement extends Component
{
    use WithPagination;

    public $filterStatus = '';

    public function render()
    {
        $sellerId = Auth::id();

        $items = OrderItem::whereHas('product', function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId);
            })
            ->with(['order.user', 'product'])
            ->when($this->filterStatus, function ($query) {
                return $query->where('status', $this->filterStatus);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.seller.order-management', [
            'items' => $items
        ]);
    }

    public function updateStatus($itemId, $status)
    {
        $item = OrderItem::find($itemId);

        // Verify ownership
        if ($item && $item->product->seller_id === Auth::id()) {
            $item->update(['status' => $status]);
            
            session()->flash('message', 'Order item status updated successfully.');
        }
    }
}
