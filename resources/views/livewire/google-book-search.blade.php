<div class="p-6 space-y-8 bg-gray-50 min-h-screen">
    <h1 class="text-3xl font-bold text-gray-900 uppercase tracking-tighter italic">Import Books from Google Books API</h1>
    <p class="text-gray-600 font-medium">Search by title, author, or ISBN to fetch external data and add it to your collection.</p>

    {{-- Search Form --}}
    <div class="bg-white p-6 rounded-xl border-4 border-gray-900 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
        <form wire:submit.prevent="search" class="flex flex-col sm:flex-row gap-4">
            {{-- Input com texto cinza escuro (text-gray-800) --}}
            <input type="text"
                   wire:model.defer="searchTerm"
                   placeholder="Title, Author, or ISBN..."
                   class="input input-bordered w-full sm:flex-grow border-2 border-gray-300 focus:border-blue-600 focus:ring-0 text-gray-800 font-bold placeholder-gray-400 transition duration-150 ease-in-out"
                   required>

            <button type="submit" 
                    wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center px-6 py-2.5 bg-blue-600 text-white font-black uppercase text-xs tracking-widest border-4 border-gray-900 rounded-xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:bg-blue-700 hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                
                {{-- Spinner de Carregamento --}}
                <svg wire:loading wire:target="search" 
                     class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" 
                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>

                <span wire:loading.remove wire:target="search">Search Books</span>
                <span wire:loading wire:target="search">Searching...</span>
            </button>
        </form>
        
        @error('searchTerm') <p class="text-red-600 font-bold text-sm mt-2">{{ $message }}</p> @enderror
        
        @if (session()->has('error'))
            <div class="alert bg-red-100 border-2 border-red-600 text-red-600 mt-4 font-bold rounded-lg"><span>{{ session('error') }}</span></div>
        @endif
        @if (session()->has('success'))
            <div class="alert bg-green-100 border-2 border-green-600 text-green-600 mt-4 font-bold rounded-lg"><span>{{ session('success') }}</span></div>
        @endif
    </div>

    {{-- Search Results --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @if ($isLoading)
            <div class="col-span-full flex flex-col items-center justify-center py-20">
                <span class="loading loading-spinner loading-lg text-blue-600"></span>
                <p class="text-gray-900 font-black uppercase italic mt-4">Connecting to Google API...</p>
            </div>
        @elseif ($searchPerformed && empty($results))
            <div class="col-span-full flex justify-center py-10">
                <p class="text-gray-500 font-bold">No books found for "{{ $searchTerm }}".</p>
            </div>
        @else
            @foreach($results as $book)
                <div class="bg-white border-4 border-gray-900 rounded-[2rem] shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] overflow-hidden hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all duration-200">
                    <div class="p-6">
                        <div class="flex gap-4">
                            {{-- Cover Image --}}
                            <div class="flex-shrink-0">
                                <img src="{{ $book['cover_image'] ?? 'https://placehold.co/128x192/E0E0E0/333?text=No+Cover' }}" 
                                     alt="Book Cover" 
                                     onerror="this.src='https://placehold.co/128x192/E0E0E0/333?text=No+Cover';"
                                     class="w-24 h-36 object-cover rounded-xl border-2 border-gray-900 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                            </div>

                            {{-- Details --}}
                            <div class="flex-grow">
                                <h2 class="text-lg font-black text-gray-900 leading-tight line-clamp-2 uppercase italic" title="{{ $book['title'] }}">
                                    {{ $book['title'] }}
                                </h2>
                                <p class="text-[10px] font-bold text-blue-600 uppercase mt-2">
                                    {{ $book['publisher_name'] }} ({{ $book['year'] }})
                                </p>
                                <p class="text-[10px] font-bold text-gray-500 mt-1">
                                    ISBN: {{ $book['isbn'] }}
                                </p>
                                <div class="mt-2 text-[10px] text-gray-700 font-medium line-clamp-3 leading-relaxed">
                                    {{ $book['bibliography'] }}
                                </div>
                            </div>
                        </div>

                        {{-- Action Button --}}
                        <div class="mt-6 flex justify-end">
                            <button wire:click="importBook({{ json_encode($book) }})" 
                                    class="px-4 py-2 bg-green-500 text-black font-black uppercase text-[10px] border-2 border-gray-900 rounded-lg shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-0.5 hover:translate-y-0.5 transition-all"
                                    wire:confirm="Import '{{ $book['title'] }}' to your collection?"
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