<div class="p-6">

    {{-- SEARCH + FILTERS --}}
    <div class="flex flex-col md:flex-row md:items-center gap-4 mb-6">

        {{-- Search --}}
            <input 
    type="text" 
    placeholder="Search books..." 
    class="input input-bordered w-full max-w-xs"
    wire:model.live="search"
/>

        {{-- Publisher Filter --}}
        <select 
            wire:model.live="filterPublisher"
            class="select select-bordered w-full md:w-1/4"
        >
            <option value="">All Publishers</option>
            @foreach($publishers as $publisher)
                <option value="{{ $publisher->id }}">
                    {{ $publisher->name }}
                </option>
            @endforeach
        </select>

    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th wire:click="sortBy('title')" class="cursor-pointer">
                        Title
                        @if($sortField === 'title')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>

                    <th wire:click="sortBy('isbn')" class="cursor-pointer">
                        ISBN
                        @if($sortField === 'isbn')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>

                    <th wire:click="sortBy('publisher')" class="cursor-pointer">
                          Publisher
                        @if($sortField === 'publisher')
                     <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                       </th>



                    <th wire:click="sortBy('year')" class="cursor-pointer">
                        Year
                        @if($sortField === 'year')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                </tr>
            </thead>

            <tbody>
                @forelse ($books as $book)
                    <tr>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->isbn }}</td>
                        <td>{{ $book->publisher->name ?? '-' }}</td>
                        <td>{{ $book->year }}</td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="4" class="text-center">No books found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $books->links() }}
    </div>

</div>
