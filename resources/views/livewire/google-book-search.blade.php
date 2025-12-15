<div class="p-6 space-y-8 bg-gray-50 min-h-screen">
    <h1 class="text-3xl font-bold text-gray-900">Import Books from Google Books API</h1>
    <p class="text-gray-600">Search by title, author, or ISBN to fetch external data and add it to your collection.</p>

    {{-- Search Form --}}
    <div class="bg-white p-6 rounded-xl shadow">
        <form wire:submit.prevent="search" class="flex flex-col sm:flex-row gap-4">
            <input type="text"
                   wire:model.defer="searchTerm"
                   placeholder="Title, Author, or ISBN..."
                   class="input input-bordered w-full sm:flex-grow focus:border-blue-500 transition duration-150 ease-in-out"
                   required>

            <button type="submit" class="btn btn-primary text-white sm:w-auto" wire:loading.attr="disabled">
                <svg wire:loading wire:target="search" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove>Search</span>
            </button>
        </form>
        @error('searchTerm') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
        
        @if (session()->has('error'))
            <div class="alert alert-error mt-4 shadow-lg"><span>{{ session('error') }}</span></div>
        @endif
        @if (session()->has('success'))
            <div class="alert alert-success mt-4 shadow-lg"><span>{{ session('success') }}</span></div>
        @endif
    </div>

    {{-- Search Results --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @if ($isLoading)
            <div class="col-span-full flex justify-center py-10">
                <span class="loading loading-spinner text-primary"></span>
                <p class="text-gray-500 ml-3">Searching for books...</p>
            </div>
        @elseif ($searchPerformed && empty($results))
            <div class="col-span-full flex justify-center py-10">
                <p class="text-gray-500">No books found for "{{ $searchTerm }}".</p>
            </div>
        @else
            @foreach($results as $book)
                <div class="card bg-white shadow-xl hover:shadow-2xl transition duration-300 ease-in-out">
                    <div class="card-body p-6">
                        <div class="flex space-x-4">
                            {{-- Cover Image --}}
                            <div class="flex-shrink-0">
                                @if($book['cover_image'])
                                    <img src="{{ $book['cover_image'] }}" 
                                         alt="Book Cover" 
                                         onerror="this.onerror=null;this.src='https://placehold.co/128x192/E0E0E0/333?text=No+Cover';"
                                         class="w-24 h-36 object-cover rounded shadow-md">
                                @else
                                    <img src="https://placehold.co/128x192/E0E0E0/333?text=No+Cover"
                                         alt="No Cover"
                                         class="w-24 h-36 object-cover rounded shadow-md">
                                @endif
                            </div>

                            {{-- Mapped Details --}}
                            <div class="flex-grow">
                                <h2 class="card-title text-xl font-bold text-blue-600 line-clamp-2" title="{{ $book['title'] }}">{{ $book['title'] }}</h2>
                                <p class="text-sm text-gray-500 mt-1">
                                    **Publisher:** {{ $book['publisher_name'] }} (Year: {{ $book['year'] }})
                                </p>
                                <p class="text-sm text-gray-600 mt-2">
                                    **ISBN:** {{ $book['isbn'] }}
                                </p>
                                <div class="mt-2 text-xs text-gray-700 line-clamp-3" title="{{ $book['bibliography'] }}">
                                    {{ $book['bibliography'] }}
                                </div>
                            </div>
                        </div>

                        {{-- Action Button (Import) --}}
                        <div class="card-actions justify-end mt-4">
                            {{-- CHAMA O MÉTODO DE IMPORTAÇÃO, PASSANDO O ARRAY DO LIVRO --}}
                            <button wire:click="importBook({{ json_encode($book) }})" 
                                    class="btn btn-sm btn-success text-black"
                                    wire:confirm="Are you sure you want to import the book '{{ $book['title'] }}'?"
                                    wire:loading.attr="disabled">
                                Import to DB
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>