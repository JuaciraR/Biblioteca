<div class="p-6 max-w-4xl mx-auto">

    <h1 class="text-3xl font-black mb-6 text-gray-900 uppercase tracking-tight">Publishers</h1>

    {{-- Search com borda visível --}}
    <div class="mb-6">
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search publisher by name..."
            class="w-full max-w-md p-3 border-2 border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:border-indigo-600 focus:ring-0 transition"
        />
    </div>

    {{-- Tabela com Alto Contraste --}}
    <div class="overflow-hidden shadow-md rounded-xl border border-gray-300 bg-white">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 border-b-2 border-gray-300">
                <tr>
                    <th class="p-4 cursor-pointer text-gray-900 font-bold uppercase text-xs tracking-widest hover:bg-gray-200 transition" wire:click="sortBy('name')">
                        <div class="flex items-center">
                            Name
                            @if($sortField === 'name')
                                <span class="ml-2 text-indigo-600">@if($sortDirection === 'asc') ↑ @else ↓ @endif</span>
                            @endif
                        </div>
                    </th>
                    <th class="p-4 text-center text-gray-900 font-bold uppercase text-xs tracking-widest">
                        Publisher Logo
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse($publishers as $publisher)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-gray-900 font-semibold text-base italic">
                            {{ $publisher->name }}
                        </td>
                        <td class="p-4">
                            @if($publisher->logo)
                                <div class="flex flex-col items-center">
                                   <img src="{{ $publisher->logo }}" 
                                        class="h-20 w-20 rounded-lg object-contain border border-gray-200 bg-white p-1 shadow-sm" 
                                        alt="{{ $publisher->name }}">
                                </div>
                            @else
                                <div class="text-center text-gray-400 font-bold uppercase text-[10px]">No Logo</div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="p-10 text-center text-gray-600 font-medium">
                            No publishers found matching your search.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>