<div class="mt-16 border-t pt-10">
    <h3 class="text-2xl font-bold text-gray-800 mb-8 flex items-center gap-2">
        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
        Related Books
    </h3>

    @if(count($relatedBooks) > 0)
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($relatedBooks as $related)
                <a href="{{ route('books.show', $related->id) }}" class="group">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="aspect-[3/4] bg-gray-100 relative">
                            @if($related->cover_image)
                                <img src="{{ $related->cover_image }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-300">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <h4 class="font-bold text-gray-900 text-sm truncate group-hover:text-blue-600">{{ $related->title }}</h4>
                            <p class="text-xs text-gray-500 mt-1 italic">{{ $related->publisher->name ?? 'Unknown Publisher' }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-gray-50 rounded-lg p-6 text-center text-gray-500 italic">
            No related books found for this title.
        </div>
    @endif
</div>
