<?php

namespace App\Livewire\Cart;

use Livewire\Component;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On; // IMPORTANTE

class CartIcon extends Component
{
    public $count = 0;

    // Este atributo substitui os listeners antigos e é muito mais fiável
    #[On('cart-updated')] 
    public function updateCount()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            $this->count = $cart ? $cart->items()->sum('quantity') : 0;
        }
    }

    public function mount()
    {
        $this->updateCount();
    }

    public function render()
    {
        // Alterado para mostrar sempre o ícone, permitindo-te ver se ele está no menu
        return <<<'HTML'
            <div class="flex items-center ml-4">
                <a href="{{ route('cart') }}" class="relative p-2 text-gray-600 hover:text-indigo-600 transition group">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    
                    @if($count > 0)
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-[10px] font-black leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-rose-600 rounded-full shadow-sm">
                            {{ $count }}
                        </span>
                    @endif
                </a>
            </div>
        HTML;
    }
}