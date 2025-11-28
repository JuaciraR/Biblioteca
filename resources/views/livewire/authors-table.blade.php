<div class="p-6 max-w-4xl mx-auto">

    <!-- SEARCH -->
    <div class="flex flex-col md:flex-row md:items-center gap-4 mb-6">
        <input 
            type="text" 
            placeholder="Search authors..." 
            class="input input-bordered w-full max-w-xs"
            wire:model.live="search"
        />
    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto shadow rounded-lg">
        <table class="table table-zebra w-full text-center">
            
            <thead class="bg-gray-100">
                <tr>
                    <th wire:click="sortBy('name')" class="cursor-pointer">
                        Name
                        @if($sortField === 'name')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                     <th class="cursor-pointer" wire:click="sortBy('photo')">
                        Photo
                        @if($sortField === 'photo')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                </tr>
            </thead>

            <tbody>
                @forelse ($authors as $author)
                    <tr>
                        <td class="py-3">{{ $author->name }}</td>

                        <td class="py-3">
                            @if($author->photo)
                                <div class="flex flex-col items-center">
                                    <img src="{{ $author->photo }}" class="h-16 w-16 rounded-full object-cover" alt="{{ $author->name }}">
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="py-4 text-gray-500">
                            No authors found.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>
