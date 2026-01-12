<div class="p-6 max-w-4xl mx-auto">

    <div class="flex flex-col md:flex-row md:items-center gap-4 mb-6">
        <input 
            type="text" 
            placeholder="Search authors..." 
            class="input input-bordered w-full max-w-xs border-gray-400 text-gray-900 placeholder-gray-500 focus:border-indigo-600"
            wire:model.live="search"
        />
    </div>

    <div class="overflow-x-auto shadow-md rounded-lg border border-gray-200">
        <table class="table table-zebra w-full text-center">
            
            {{-- Table Head with Bold Black Text --}}
            <thead class="bg-gray-100 border-b border-gray-300">
                <tr class="text-gray-900 font-bold uppercase text-xs tracking-wider">
                    <th wire:click="sortBy('name')" class="py-4 cursor-pointer hover:bg-gray-200 transition">
                        Name
                        @if($sortField === 'name')
                            <span class="ml-1 text-indigo-600">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th class="py-4 cursor-pointer hover:bg-gray-200 transition" wire:click="sortBy('photo')">
                        Photo
                        @if($sortField === 'photo')
                            <span class="ml-1 text-indigo-600">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                </tr>
            </thead>

            {{-- Table Body with High Contrast Text --}}
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse ($authors as $author)
                    <tr class="hover:bg-gray-50">
                        <td class="py-4 px-6 text-sm font-semibold text-gray-900">
                            {{ $author->name }}
                        </td>

                        <td class="py-4 px-6">
                            @if($author->photo)
                                <div class="flex justify-center">
                                    <img src="{{ $author->photo }}" 
                                         class="h-12 w-12 rounded-full object-cover border-2 border-gray-200 shadow-sm" 
                                         alt="{{ $author->name }}">
                                </div>
                            @else
                                <span class="text-gray-400 font-bold">No Photo</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="py-10 text-gray-600 font-medium">
                            <i class="fas fa-search mb-2 block text-xl opacity-20"></i>
                            No authors found for "<span class="font-bold text-gray-900">{{ $search }}</span>"
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>