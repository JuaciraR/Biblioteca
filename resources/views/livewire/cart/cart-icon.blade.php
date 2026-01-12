<div class="flex items-center">
    <a href="{{ route('cart') }}" class="relative group p-2">
        {{-- O ícone real do Font Awesome deve estar aqui sozinho --}}
       <i class="fas fa-shopping-cart text-xl"></i>

        {{-- Badge do Contador por cima do ícone --}}
        @if($count > 0)
            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full border-2 border-white shadow-sm">
                {{ $count }}
            </span>
        @endif
    </a>
</div>