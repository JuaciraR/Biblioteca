<div class="max-w-5xl mx-auto py-12 px-4">
    <h1 class="text-4xl font-black text-gray-900 mb-8 uppercase tracking-tighter italic">Shopping Cart</h1>

    @if($items && $items->count() > 0)
        <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden">
            <div class="divide-y divide-gray-100">
                @foreach($items as $item)
                    <div class="p-8 flex flex-col md:flex-row items-center gap-8 hover:bg-gray-50/50 transition-colors">
                        {{-- Book Cover --}}
                        <img src="{{ $item->book->cover_image }}" class="w-24 h-36 object-cover rounded-2xl shadow-lg border border-gray-100">

                        {{-- Details --}}
                        <div class="flex-1 text-center md:text-left">
                            {{-- Título ajustado para Cinza Escuro quase Preto --}}
                            <h3 class="font-black text-xl text-gray-900 leading-tight mb-1">{{ $item->book->title }}</h3>
                            <p class="text-indigo-600 font-bold mb-4 uppercase text-xs tracking-widest italic">
                                €{{ number_format((float)$item->book->price, 2) }} / unit
                            </p>
                            
                            {{-- Quantity Control --}}
                            <div class="flex items-center justify-center md:justify-start gap-4">
                                {{-- Input de Quantidade com texto escuro e borda definida --}}
                                <input type="number" 
                                       wire:change="updateQuantity({{ $item->id }}, $event.target.value)" 
                                       value="{{ $item->quantity }}" 
                                       min="1" 
                                       class="w-20 rounded-xl border-2 border-gray-300 font-black text-gray-900 text-center focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                                
                                <button wire:click="removeItem({{ $item->id }})" class="text-rose-500 text-[10px] font-black uppercase tracking-widest hover:underline decoration-2">
                                    Remove Item
                                </button>
                            </div>
                        </div>

                        {{-- Item Subtotal --}}
                        <div class="text-right">
                            <p class="text-2xl font-black text-gray-900 italic">
                                €{{ number_format((float)$item->book->price * $item->quantity, 2) }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Cart Footer --}}
            <div class="bg-gray-900 p-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <span class="text-gray-400 text-[10px] font-black uppercase tracking-widest block mb-1">Estimated Total</span>
                    <p class="text-4xl font-black text-white italic">€{{ number_format($total, 2) }}</p>
                </div>
                
                <a href="{{ route('checkout') }}" class="bg-white text-gray-900 font-black py-4 px-12 rounded-2xl hover:bg-indigo-500 hover:text-white transition-all duration-300 shadow-xl uppercase tracking-tighter">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    @else
        <div class="text-center py-24 bg-white rounded-[3rem] border-4 border-dashed border-gray-100">
            <h2 class="text-2xl font-bold text-gray-400 mb-6 italic">Your cart is currently empty...</h2>
            <a href="{{ route('books') }}" class="bg-indigo-600 text-white font-black py-4 px-8 rounded-2xl hover:bg-indigo-700 transition shadow-lg uppercase tracking-tight">
                Browse Books
            </a>
        </div>
    @endif
</div>