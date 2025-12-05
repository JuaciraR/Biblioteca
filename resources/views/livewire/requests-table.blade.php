<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Requisições') }}
        </h2>
    </x-slot>

    {{-- CHAMA O COMPONENTE LIVEWIRE DE GESTÃO --}}
    <livewire:admin-request-management />
</x-app-layout>