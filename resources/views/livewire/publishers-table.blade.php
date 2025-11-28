<div class="p-6 max-w-3xl mx-auto">

    <h1 class="text-2xl font-bold mb-4">Publishers</h1>

    {{-- Search --}}
    <input
        type="text"
        wire:model.live="search"
        placeholder="Search publisher..."
        class="w-full mb-4 p-2 border rounded"
    />

    {{-- Table --}}
    <table class="w-full border border-gray-300 text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 cursor-pointer" wire:click="sortBy('name')">
                    Name
                    @if($sortField === 'name')
                        @if($sortDirection === 'asc') ↑ @else ↓ @endif
                    @endif
                </th>
                <th class="cursor-pointer p-2 text-center" wire:click="sortBy('logo')">
                    Logo
                    @if($sortField === 'logo')
                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                    @endif
                </th>
            </tr>
        </thead>

        <tbody>
            @forelse($publishers as $publisher)
                <tr class="border-t">
                    <td class="p-2">{{ $publisher->name }}</td>
                    <td class="p-2 text-center">
                        @if($publisher->logo)
                            <div class="flex flex-col items-center">
                                <span class="mb-1 text-sm text-gray-600">Logo</span>
                               <img src="{{ $publisher->logo }}" class="h-24 w-24 rounded-full object-cover mx-auto" alt="{{ $publisher->name }}">

                            </div>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="p-4 text-center text-gray-500">No publishers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
