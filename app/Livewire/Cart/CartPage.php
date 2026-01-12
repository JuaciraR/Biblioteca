<?php

namespace App\Livewire\Cart;

use Livewire\Component;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartPage extends Component
{
    public $cart;
    public $total = 0;

    /**
     * Listeners to allow adding items from other components (like BookDetail).
     */
    protected $listeners = ['itemAdded' => 'mount'];

    public function mount()
    {
        if (Auth::check()) {
            $this->cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $this->calculateTotal();
        }
    }

    public function calculateTotal()
    {
        $this->total = $this->cart->items->sum(function ($item) {
            return $item->book->price * $item->quantity;
        });
    }

    public function updateQuantity($itemId, $quantity)
    {
        $item = CartItem::find($itemId);
        if ($item && $quantity > 0) {
            $item->update(['quantity' => $quantity]);
            $this->cart->touch(); // Important for the 1-hour abandoned cart logic
        }
        $this->calculateTotal();
    }

    public function removeItem($itemId)
    {
        CartItem::destroy($itemId);
        $this->mount();
    }

    public function render()
    {
        return view('livewire.cart.cart-page', [
            'items' => $this->cart ? $this->cart->items()->with('book')->get() : collect([])
        ]);
    }
}