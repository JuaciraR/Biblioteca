<div class="bg-white p-8 rounded-[2rem] shadow-2xl border-2 border-gray-100">
    <h3 class="text-3xl font-black text-gray-900 mb-6 uppercase tracking-tighter italic border-b-4 border-gray-100 pb-2">
        Leave Your Review
    </h3>


    @if (session()->has('review_success'))
        <div class="p-5 mb-6 text-sm font-black text-green-950 bg-green-500 border-4 border-green-600 rounded-2xl uppercase tracking-widest shadow-md" role="alert">
            <span class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-xl text-green-700"></i> 
                Success! {{ session('review_success') }}
            </span>
        </div>
    @endif
    
    
    @if (session()->has('review_error'))
        <div class="p-5 mb-6 text-sm font-black text-red-950 bg-red-500 border-4 border-red-600 rounded-2xl uppercase tracking-widest shadow-md" role="alert">
            <span class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-3 text-xl text-red-700"></i> 
                Error! {{ session('review_error') }}
            </span>
        </div>
    @endif
    <form wire:submit.prevent="submitReview" class="space-y-6">
        
        {{-- Rating Field --}}
        <div>
            <label for="rating" class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-widest italic">
                Rating (1 to 5 Stars)
            </label>
            <select wire:model.defer="rating" id="rating" 
                    class="block w-full pl-4 pr-10 py-3 text-base border-2 border-gray-400 text-gray-900 font-bold focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 rounded-xl shadow-sm bg-white cursor-pointer">
                <option value="5" class="font-bold">5 Stars ★★★★★ (Excellent)</option>
                <option value="4" class="font-bold">4 Stars ★★★★☆ (Very Good)</option>
                <option value="3" class="font-bold">3 Stars ★★★☆☆ (Good)</option>
                <option value="2" class="font-bold">2 Stars ★★☆☆☆ (Fair)</option>
                <option value="1" class="font-bold">1 Star ★☆☆☆☆ (Poor)</option>
            </select>
            @error('rating') <p class="text-rose-600 font-black text-[10px] mt-2 uppercase tracking-widest">{{ $message }}</p> @enderror
        </div>

        {{-- Comment Field --}}
        <div>
            <label for="comment" class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-widest italic">
                Comment / Review (Optional)
            </label>
            <textarea wire:model.defer="comment" id="comment" rows="4" 
                      class="shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 block w-full border-2 border-gray-400 text-gray-900 font-bold rounded-xl p-4 placeholder-gray-500 bg-white" 
                      placeholder="Share your opinion on the book (max. 500 characters)."></textarea>
            @error('comment') <p class="text-rose-600 font-black text-[10px] mt-2 uppercase tracking-widest">{{ $message }}</p> @enderror
        </div>

        {{-- Submission Button --}}
        <div class="flex justify-end pt-2">
            <button type="submit" 
                    class="w-full md:w-auto inline-flex justify-center py-4 px-10 border-none shadow-xl text-sm font-black rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 uppercase tracking-tighter" 
                    wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="submitReview">Submit Review</span>
                <span wire:loading wire:target="submitReview" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Submitting...
                </span>
            </button>
        </div>
    </form>
</div>