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
        class="select select-bordered w-full md:w-1/4 border-2 border-gray-500 text-gray-900 font-black text-sm bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600 appearance-none">
    <option value="" class="font-bold text-gray-500">All Publishers</option>
    @foreach($publishers as $publisher)
        <option value="{{ $publisher->id }}" class="text-gray-900 font-semibold">
            {{ $publisher->name }}
        </option>
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


{{-- BOOKS TABLE - ENHANCED SIZE & CONTRAST --}}
<div class="w-full overflow-x-auto rounded-[2.5rem] border-4 border-gray-900 shadow-2xl bg-white scrollbar-thin scrollbar-thumb-gray-900">
    <table class="table w-full border-collapse">
        <thead class="bg-gray-900 border-b-4 border-gray-900">
            <tr class="text-white font-black uppercase text-[13px] tracking-widest text-center">
                {{-- Todos os campos com wire:click e ícones maiores --}}
                <th class="p-6 cursor-pointer hover:bg-gray-800 transition-colors" wire:click="sortBy('title')">
                    Title <span class="text-lg ml-1 text-blue-400">@if($sortField === 'title') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @else ↕ @endif</span>
                </th>
                <th class="p-6 cursor-pointer hover:bg-gray-800 transition-colors" wire:click="sortBy('isbn')">
                    ISBN <span class="text-lg ml-1 text-blue-400">@if($sortField === 'isbn') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @else ↕ @endif</span>
                </th>
                <th class="p-6 cursor-pointer hover:bg-gray-800 transition-colors" wire:click="sortBy('publisher_id')">
                    Publisher <span class="text-lg ml-1 text-blue-400">@if($sortField === 'publisher_id') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @else ↕ @endif</span>
                </th>
                <th class="p-6 cursor-pointer hover:bg-gray-800 transition-colors" wire:click="sortBy('year')">
                    Year <span class="text-lg ml-1 text-blue-400">@if($sortField === 'year') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @else ↕ @endif</span>
                </th>
                <th class="p-6 cursor-pointer hover:bg-gray-800 transition-colors" wire:click="sortBy('price')">
                    Price <span class="text-lg ml-1 text-blue-400">@if($sortField === 'price') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @else ↕ @endif</span>
                </th>
                <th class="p-6 cursor-pointer hover:bg-gray-800 transition-colors" wire:click="sortBy('bibliography')">
                    Bibliography <span class="text-lg ml-1 text-blue-400">@if($sortField === 'bibliography') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @else ↕ @endif</span>
                </th>
                <th class="p-6 cursor-pointer hover:bg-gray-800 transition-colors" wire:click="sortBy('cover_image')">
                    Cover <span class="text-lg ml-1 text-blue-400">@if($sortField === 'cover_image') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @else ↕ @endif</span>
                </th>
                <th class="p-6">Availability</th>
                <th class="p-6">Actions</th>
            </tr>
        </thead>

        <tbody class="text-center divide-y-4 divide-gray-200">
            @forelse ($books as $book)
                <tr class="hover:bg-gray-50 transition-all">
                    {{-- Fonte aumentada para text-base (16px) e negrito pesado --}}
                    <td class="p-6 font-black text-gray-900 text-base italic leading-tight">{{ $book->title }}</td>
                    <td class="p-6 text-gray-950 font-bold text-sm">{{ $book->isbn }}</td>
                    <td class="p-6 text-gray-900 font-black uppercase text-xs">{{ $book->publisher->name ?? '-' }}</td>
                    <td class="p-6 text-gray-950 font-black text-lg">{{ $book->year ?? '-' }}</td>
                    <td class="p-6 text-blue-900 font-black text-lg tracking-tighter">€{{ number_format($book->price, 2) }}</td>
                    <td class="p-6 text-gray-800 font-bold text-xs max-w-xs truncate">{{ $book->bibliography ?? '-' }}</td>
                    
                    <td class="p-6">
                        @if($book->cover_image)
                            <img src="{{ $book->cover_image }}" class="h-24 w-20 object-cover rounded-xl border-4 border-gray-900 mx-auto shadow-lg bg-white">
                        @else
                            <div class="h-24 w-20 bg-gray-100 rounded-xl border-4 border-dashed border-gray-400 mx-auto flex items-center justify-center">
                                <span class="text-gray-400 font-black italic">X</span>
                            </div>
                        @endif
                    </td>

                    <td class="p-6">
                        @php
                            $isAvailable = $book->isAvailableForRequest();
                            $status = $isAvailable ? 'Available' : 'In Request';
                            $status_style = $isAvailable ? 'bg-green-700 text-white border-green-950 shadow-[0_4px_0_0_rgba(5,46,22,1)]' : 'bg-red-700 text-white border-red-950 shadow-[0_4px_0_0_rgba(69,10,10,1)]';
                        @endphp
                        <span class="border-2 font-black uppercase text-xs px-5 py-2.5 rounded-xl inline-block {{ $status_style }}">
                            {{ __($status) }}
                        </span>
                    </td>

                    <td class="p-6">
                        <div class="flex flex-col gap-2 items-center justify-center min-w-[150px]">
                            <a href="{{ route('books.show', $book->id) }}" class="w-full py-2.5 bg-white border-4 border-gray-900 text-gray-900 font-black rounded-xl uppercase text-xs hover:bg-gray-900 hover:text-white transition-all text-center">
                                Details
                            </a>
                            
                            @if($isAdmin)
                                <div class="flex gap-2 w-full">
                                    <button wire:click="editBook({{ $book->id }})" class="flex-1 py-2.5 bg-amber-500 border-4 border-amber-800 text-black font-black rounded-xl uppercase text-xs hover:bg-amber-600 shadow-md">
                                        Edit
                                    </button>
                                    <button wire:click="deleteBook({{ $book->id }})" class="flex-1 py-2.5 bg-red-700 border-4 border-red-950 text-white font-black rounded-xl uppercase text-xs hover:bg-red-800 shadow-md">
                                        Delete
                                    </button>
                                </div>
                            @endif
                            
                            @if(auth()->user()?->getAttribute('role') === 'Cidadao')
                                <div class="w-full transform scale-110 mt-1">
                                    <livewire:book-request-button :book="$book" :key="'request-'.$book->id" />
                                </div>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="p-20 text-gray-900 font-black uppercase text-xl italic tracking-widest bg-gray-50 text-center">
                        NO BOOKS FOUND IN DATABASE
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
<div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4 backdrop-blur-sm">
    
    {{-- MODAL CONTAINER: Adicionamos h-[95vh] e flex-col --}}
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md h-auto max-h-[95vh] flex flex-col border-2 border-gray-200">

        {{-- HEADER: Fixed at the top --}}
        <div class="p-8 pb-4 flex-shrink-0 border-b border-gray-100">
            <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter italic">
                {{ $bookId ? 'Edit Book' : 'Add Book' }}
            </h2>
        </div>

        {{-- FORM BODY: This is the part that will scroll --}}
        <form wire:submit.prevent="saveBook" id="bookForm" 
              class="space-y-6 flex-grow overflow-y-auto p-8 pt-6 scrollbar-thin scrollbar-thumb-gray-300">
            
            {{-- Title --}}
            <div>
                <label class="block mb-2 text-sm font-black text-gray-900 uppercase tracking-widest italic">Title</label>
                <input type="text" wire:model="title" class="input input-bordered w-full border-2 border-gray-400 text-gray-900 font-bold focus:border-indigo-600 bg-white h-12">
            </div>

            {{-- ISBN --}}
            <div>
                <label class="block mb-2 text-sm font-black text-gray-900 uppercase tracking-widest italic">ISBN</label>
                <input type="text" wire:model="isbn" class="input input-bordered w-full border-2 border-gray-400 text-gray-900 font-bold focus:border-indigo-600 bg-white h-12">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2 text-sm font-black text-gray-900 uppercase tracking-widest italic">Year</label>
                    <input type="number" wire:model="year" class="input input-bordered w-full border-2 border-gray-400 text-gray-900 font-bold focus:border-indigo-600 bg-white h-12">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-black text-gray-900 uppercase tracking-widest italic">Price (€)</label>
                    <input type="number" step="0.01" wire:model="price" class="input input-bordered w-full border-2 border-gray-400 text-gray-900 font-bold focus:border-indigo-600 bg-white h-12">
                </div>
            </div>

            {{-- Publisher --}}
            <div>
                <label class="block mb-2 text-sm font-black text-gray-900 uppercase tracking-widest italic">Publisher</label>
                <select wire:model="publisher_id" class="select select-bordered w-full border-2 border-gray-400 text-gray-900 font-black focus:border-indigo-600 bg-white h-12">
                    <option value="">-- Select Publisher --</option>
                    @foreach($publishers as $publisher)
                        <option value="{{ $publisher->id }}">{{ $publisher->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Bibliography --}}
            <div>
                <label class="block mb-2 text-sm font-black text-gray-900 uppercase tracking-widest italic">Bibliography</label>
                <textarea wire:model="bibliography" class="textarea textarea-bordered w-full border-2 border-gray-400 text-gray-900 font-bold focus:border-indigo-600 bg-white min-h-[100px]"></textarea>
            </div>

            {{-- Cover Image Section --}}
            <div class="bg-gray-50 p-4 rounded-2xl border-2 border-dashed border-gray-300">
                <label class="block mb-4 text-sm font-black text-gray-900 uppercase tracking-widest italic text-center">Cover Image</label>
                <input type="file" wire:model="newCover" class="file-input file-input-bordered w-full border-gray-400 text-gray-900 font-bold bg-white">
            </div>
        </form>

        {{-- FOOTER: Fixed at the bottom --}}
    
        <div class="flex justify-end gap-3 flex-shrink-0 bg-gray-50 border-t-2 border-gray-200 p-5 rounded-b-[2rem]">
            <button type="button" wire:click="$set('isModalOpen', false)" 
                    class="px-4 py-2 border-2 border-gray-400 text-gray-900 font-black rounded-xl hover:bg-gray-200 uppercase text-xs">
                Cancel
            </button>
            <button type="submit" form="bookForm" 
                    class="px-8 py-2 bg-indigo-600 text-white font-black rounded-xl shadow-lg hover:bg-indigo-700 uppercase text-xs">
                Save Book
            </button>
        </div>
    </div>
</div>
@endif