<div class="p-6 space-y-6">

    {{-- SEARCH + FILTERS + ACTIONS (EXPORT/CREATE) --}}
    <div class="bg-white p-4 rounded-xl shadow flex flex-wrap items-center gap-4">

        {{-- SEARCH INPUT --}}
        <input type="text" 
               placeholder="Search books..." 
               class="input input-bordered w-full md:max-w-xs" 
               wire:model.live="search"/>

        {{-- PUBLISHER FILTER --}}
        <select wire:model.live="filterPublisher" 
                class="select select-bordered w-full md:w-1/4">
            <option value="">All Publishers</option>
            @foreach($publishers as $publisher)
                <option value="{{ $publisher->id }}">{{ $publisher->name }}</option>
            @endforeach
        </select>

        <div class="ml-auto flex gap-3">
            {{-- EXPORT BUTTON --}}
          <a href="{{ route('books.export') }}" 
        class="btn btn-success text-black">
        
  
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
        Export to Excel
    </a>

            {{-- CREATE BUTTON (ADMIN ONLY) --}}
          @if($isAdmin)
        <button wire:click="createBook" 
            class="btn btn-primary text-black">
            
        
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Add Book
        </button>
    @endif
</div>
    </div>

      {{-- FLASH MESSAGES --}}
    @if (session()->has('success'))
        <div class="alert alert-success shadow-lg mt-4"><span>{{ session('success') }}</span></div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-error shadow-lg mt-4"><span>{{ session('error') }}</span></div>
    @endif


   
    {{-- BOOKS TABLE --}}
    <div class="bg-black rounded-xl shadow overflow-x-auto p-4">
        <table class="table w-full">
            <thead class="text-black-700 bg-black-100">
                <tr>
                    {{-- HEADER 1: TITLE --}}
                    <th class="cursor-pointer text-center" wire:click="sortBy('title')">
                        Title @if($sortField === 'title') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                    </th>
                    {{-- HEADER 2: ISBN --}}
                    <th class="cursor-pointer text-center" wire:click="sortBy('isbn')">
                        ISBN @if($sortField === 'isbn') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                    </th>
                    {{-- HEADER 3: PUBLISHER --}}
                    <th class="cursor-pointer text-center" wire:click="sortBy('publisher_id')">
                        Publisher @if($sortField === 'publisher_id') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                    </th>
                    {{-- HEADER 4: YEAR --}}
                    <th class="cursor-pointer text-center" wire:click="sortBy('year')">
                        Year @if($sortField === 'year') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                    </th>
                    {{-- HEADER 5: PRICE --}}
                    <th class="cursor-pointer text-center" wire:click="sortBy('price')">
                        Price @if($sortField === 'price') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                    </th>
                    {{-- HEADER 6: BIBLIOGRAPHY --}}
                    <th class="cursor-pointer text-center" wire:click="sortBy('bibliography')">
                        Bibliography @if($sortField === 'bibliography') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                    </th>
                    {{-- HEADER 7: COVER --}}
                    <th class="cursor-pointer text-center" wire:click="sortBy('cover_image')">
                        Cover @if($sortField === 'cover_image') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                    </th>
                    
                    {{-- HEADER 8: AVAILABILITY --}}
                    <th class="text-center">
                        Availability
                    </th>
                    
                    {{-- HEADER 9: ACTIONS --}}
                    <th class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody class="text-center">
                @forelse ($books as $book)
                    <tr class="hover:bg-black-50">
                        {{-- 1. TITLE --}}
                        <td class="font-medium">{{ $book->title }}</td>
                        {{-- 2-6. DADOS --}}
                        <td>{{ $book->isbn }}</td>
                        <td>{{ $book->publisher->name ?? '-' }}</td>
                        <td>{{ $book->year ?? '-' }}</td>
                        <td>{{ $book->price ? '€'.number_format($book->price, 2) : '-' }}</td>
                        <td>{{ $book->bibliography ?? '-' }}</td>

                        {{-- 7. COVER --}}
                        <td>
                            @if($book->cover_image)
                                <img src="{{ $book->cover_image }}" 
                                        class="h-20 w-20 object-cover rounded mx-auto">
                            @else
                                -
                            @endif
                        </td>
                        
                        {{-- 8. DISPONIBILIDADE --}}
                        <td>
                            @php
                                $isAvailable = $book->isAvailableForRequest();
                                $status = $isAvailable ? 'Available' : 'In Request';
                                $status_class = $isAvailable ? 'badge-success' : 'badge-error';
                            @endphp
                            <span class="badge {{ $status_class }} text-black font-bold">{{ __($status) }}</span>
                        </td>

                        {{-- 9. ACTION COLUMN (Corrigido para 100% de funcionalidade e interface) --}}
                        <td class="space-x-1 space-y-1 flex flex-col sm:flex-row items-center justify-center">
                            
                            {{-- BOTÃO DETALHES (HISTÓRICO) --}}
                            <a href="{{ route('books.show', $book->id) }}" class="btn btn-xs btn-outline">
                                {{ __('Details') }}
                            </a>
                            
                            @if($isAdmin)
                                {{-- ADMIN ACTIONS: CRUD --}}
                                <button wire:click="editBook({{ $book->id }})" 
                                    class="btn btn-xs btn-warning text-black">
                                    Edit
                                </button>
                                <button wire:click="deleteBook({{ $book->id }})"
                                    class="btn btn-xs btn-error text-black">
                                    Delete
                                </button>
                            @endif
                            
                            {{-- BOTÃO DE REQUISIÇÃO (Visível para Admin e Cidadão logado) --}}
                            @if(Auth::check() && (Auth::user()->role === 'Cidadao' || $isAdmin))
                                <livewire:book-request-button :book="$book" :key="'request-'.$book->id" />
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        {{-- Colspan ajustado para 9 colunas no total --}}
                        <td colspan="9" class="py-4 text-black-500">
                            No books found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

       
                 

    {{-- PAGINATION --}}
    <div class="pt-4">
        {{ $books->links() }}
    </div>
{{-- MODAL (EDIT/CREATE) --}}

@if ($isModalOpen)
<div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 p-4">
    
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md h-auto max-h-[90vh] flex flex-col">

        <div class="p-6 pb-0 flex-shrink-0">
            <h2 class="text-2xl font-semibold">
                {{ $bookId ? 'Edit Book' : 'Add Book' }}
            </h2>
        </div>

        <form wire:submit.prevent="saveBook" id="bookForm" 
              class="space-y-3 flex-grow overflow-y-auto p-6 pt-5">
            
            {{-- TITLE INPUT --}}
            <div>
                <label class="font-semibold">Title</label>
                <input type="text" wire:model="title" class="input input-bordered w-full">
            </div>

            {{-- ISBN INPUT --}}
            <div>
                <label class="font-semibold">ISBN</label>
                <input type="text" wire:model="isbn" class="input input-bordered w-full">
            </div>

            {{-- YEAR INPUT --}}
            <div>
                <label class="font-semibold">Year</label>
                <input type="number" wire:model="year" class="input input-bordered w-full">
            </div>

            {{-- PRICE INPUT --}}
            <div>
                <label class="font-semibold">Price</label>
                <input type="number" step="0.01" wire:model="price" class="input input-bordered w-full">
            </div>

            {{-- PUBLISHER SELECT --}}
            <div>
                <label class="font-semibold">Publisher</label>
                <select wire:model="publisher_id" class="select select-bordered w-full">
                    <option value="">-- Select Publisher --</option>
                    @foreach($publishers as $publisher)
                        <option value="{{ $publisher->id }}">{{ $publisher->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- BIBLIOGRAPHY TEXTAREA --}}
            <div>
                <label class="font-semibold">Bibliography</label>
                <textarea wire:model="bibliography" class="textarea textarea-bordered w-full"></textarea>
            </div>

            {{-- COVER IMAGE UPLOAD --}}
   {{-- COVER IMAGE UPLOAD (COM PREVIEW, MAS FORÇADO A SER PEQUENO) --}}
<div>
    <label class="font-semibold">Cover Image</label>

    {{-- CURRENT COVER PREVIEW --}}
    @if($bookId && $cover_image && !$newCover)
        <div class="mb-4">
            <span class="font-semibold">Current Cover:</span>
            <div class="mt-2 flex justify-center h-28 overflow-hidden">
                <img 
                    src="{{ asset('storage/'.$cover_image) }}" 
                    class="object-contain rounded shadow">
            </div>
        </div>
    @endif
    
    {{-- NEW COVER INPUT --}}
    <input type="file" wire:model="newCover" 
            class="file-input file-input-bordered w-full">
</div>
    
    
    <div class="h-6"></div> 
        </form>

        <div class="flex justify-end pt-4 gap-3 flex-shrink-0 bg-white border-t border-gray-200 p-6 pt-4">
            
            <button type="button" 
                    wire:click="$set('isModalOpen', false)"
                    class="btn btn-outline">
                Cancel
            </button>

            <button type="submit" form="bookForm" class="btn btn-primary">
                Save
            </button>
        </div>
    </div>
</div>
@endif