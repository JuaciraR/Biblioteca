<div class="p-6">


    {{-- SEARCH + FILTERS --}}
    <div class="flex flex-wrap items-center gap-4 mb-6">
        
        <input type="text" placeholder="Search books..." class="input input-bordered w-full max-w-xs" wire:model.live="search"/>
        


        <select wire:model.live="filterPublisher" class="select select-bordered w-full md:w-1/4">
            <option value="">All Publishers</option>
            @foreach($publishers as $publisher)
                <option value="{{ $publisher->id }}">{{ $publisher->name }}</option>
            @endforeach
        </select>
       

             <a href="{{ route('books.export') }}" 
       class="px-6 py-3 bg-green-800 text-black font-semibold rounded shadow hover:bg-green-900 transition">
        Export to Excel
    </a>
    </div>

      

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th wire:click="sortBy('title')" class="cursor-pointer text-center">
                        Title @if($sortField === 'title') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                    </th>
                    <th wire:click="sortBy('isbn')" class="cursor-pointer text-center">
                        ISBN @if($sortField === 'isbn') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                    </th>
                    <th wire:click="sortBy('publisher_id')" class="cursor-pointer text-center">
                        Publisher @if($sortField === 'publisher_id') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                    </th>
                    <th wire:click="sortBy('year')" class="cursor-pointer text-center">
                        Year @if($sortField === 'year') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                    </th>
                    <th wire:click="sortBy('price')" class="cursor-pointer text-center">
                        Price @if($sortField === 'price') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                    </th>
                    <th wire:click="sortBy('bibliography')" class="cursor-pointer text-center">
                        Bibliography @if($sortField === 'bibliography') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                    </th>
                    <th wire:click="sortBy('cover_image')" class="cursor-pointer text-center">
                        Cover @if($sortField === 'cover_image') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                    </th>
                </tr>
            </thead>

            <tbody>
                @forelse ($books as $book)
                    <tr>
                        <td class="text-center">{{ $book->title }}</td>
                        <td class="text-center">{{ $book->isbn }}</td>
                        <td class="text-center">{{ $book->publisher->name ?? '-' }}</td>
                        <td class="text-center">{{ $book->year ?? '-' }}</td>
                        <td class="text-center">{{ $book->price ? '€' . number_format($book->price, 2) : '-' }}</td>
                        <td class="text-center">{{ $book->bibliography ?? '-' }}</td>
                        <td class="text-center">
                            @if($book->cover_image)
                             <img src="{{ $book->cover_image }}" class="h-24 w-24 object-cover mx-auto rounded" alt="{{ $book->title }}">
                            @else
                                -
                            @endif
                        </td>
                     
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No books found.</td>
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
