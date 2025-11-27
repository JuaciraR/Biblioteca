<div class="p-6">
    <!-- SEARCH -->
    <div class="flex flex-col md:flex-row md:items-center gap-4 mb-6">
        <input type="text" placeholder="Search authors..." class="input input-bordered w-full max-w-xs" wire:model.live="search"/>
    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th wire:click="sortBy('name')" class="cursor-pointer text-center">
                        Name
                        @if($sortField === 'name')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th class="text-center">Photo</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($authors as $author)
                    <tr>
                        <td class="text-center">{{ $author->name }}</td>
                        <td class="text-center">
                            @if($author->photo)
                                <img src="{{ asset('storage/' . $author->photo) }}" class="h-12 w-12 rounded-full object-cover mx-auto" alt="{{ $author->name }}">
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">No authors found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="mt-4">
        {{ $authors->links() }}
    </div>
</div>
