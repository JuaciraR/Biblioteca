<div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
    <h3 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">Leave Your Review</h3>

    {{-- Feedback Messages --}}
    @if (session()->has('review_success'))
        <div class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
            <span class="font-medium">Success!</span> {{ session('review_success') }}
        </div>
    @endif
    @if (session()->has('review_error'))
        <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
            <span class="font-medium">Error!</span> {{ session('review_error') }}
        </div>
    @endif

    <form wire:submit.prevent="submitReview" class="space-y-4">
        
        {{-- Rating Field --}}
        <div>
            <label for="rating" class="block text-sm font-semibold text-gray-700 mb-1">Rating (1 to 5 Stars)</label>
            <select wire:model.defer="rating" id="rating" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm">
                <option value="5">5 Stars (Excellent)</option>
                <option value="4">4 Stars (Very Good)</option>
                <option value="3">3 Stars (Good)</option>
                <option value="2">2 Stars (Fair)</option>
                <option value="1">1 Star (Poor)</option>
            </select>
            @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Comment Field --}}
        <div>
            <label for="comment" class="block text-sm font-semibold text-gray-700 mb-1">Comment / Review (Optional)</label>
            <textarea wire:model.defer="comment" id="comment" rows="4" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Share your opinion on the book (max. 500 characters)."></textarea>
            @error('comment') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Submission Button --}}
        <div class="flex justify-end">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="submitReview">Submit Review</span>
                <span wire:loading wire:target="submitReview">Submitting...</span>
            </button>
        </div>
    </form>
</div>