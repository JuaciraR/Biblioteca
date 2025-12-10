<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ __('Book Detail') }}: {{ $book->title }}
        </h2>
    </x-slot>

    {{-- CHAMA O COMPONENTE LIVEWIRE --}}
    <livewire:book-detail :book="$book" />
    
   
    
    @livewireScripts
    
</x-app-layout>