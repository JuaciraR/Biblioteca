<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-900 leading-tight uppercase tracking-tighter italic">
            {{ __('Secure Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Chamada do componente Livewire de Checkout --}}
            @livewire('cart.checkout')
        </div>
    </div>
</x-app-layout>