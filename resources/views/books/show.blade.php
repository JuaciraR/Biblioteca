<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ __('Book Detail') }}
        </h2>
    </x-slot>

  
    <livewire:book-detail :book="$book" />
</x-app-layout>