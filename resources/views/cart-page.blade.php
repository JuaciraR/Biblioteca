<x-app-layout>
    {{-- Header Section --}}
    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-900 leading-tight uppercase tracking-tighter italic">
            {{ __('My Shopping Cart') }}
        </h2>
    </x-slot>

    {{-- Main Content --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- 
                Calling the Livewire component. 
                This will handle the book listing, quantities, and removal.
            --}}
            @livewire('cart.cart-page')
        </div>
    </div>
</x-app-layout>