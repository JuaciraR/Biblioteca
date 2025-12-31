<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            
            {{-- Header section --}}
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h2 class="text-2xl font-bold text-gray-800">Review Moderation Panel</h2>
                <div class="flex gap-4">
                    <span class="badge badge-warning text-white p-3 font-bold">
                        {{ \App\Models\Review::where('status', 'suspended')->count() }} Pending
                    </span>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-100">
                <table class="table w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="p-4 uppercase text-xs font-bold tracking-wider">Book</th>
                            <th class="p-4 uppercase text-xs font-bold tracking-wider">User</th>
                            <th class="p-4 uppercase text-xs font-bold tracking-wider">Rating</th>
                            <th class="p-4 uppercase text-xs font-bold tracking-wider">Comment</th>
                            <th class="p-4 uppercase text-xs font-bold tracking-wider">Status</th>
                            <th class="p-4 text-center uppercase text-xs font-bold tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($reviews as $review)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="p-4">
                                    <div class="font-bold text-blue-700">{{ $review->book->title }}</div>
                                    <div class="text-xs text-gray-400 italic">ISBN: {{ $review->book->isbn }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="font-medium text-gray-900">{{ $review->user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $review->user->email }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="flex text-yellow-400">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="text-xl leading-none">{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                        @endfor
                                    </div>
                                </td>
                                <td class="p-4 text-sm text-gray-600 max-w-xs italic leading-relaxed">
                                    "{{ $review->comment ?? 'No text provided' }}"
                                </td>
                                <td class="p-4">
                                    @php
                                        $badgeClass = match($review->status) {
                                            'active' => 'badge-success',
                                            'rejected' => 'badge-error',
                                            default => 'badge-warning',
                                        };
                                        $statusLabel = match($review->status) {
                                            'active' => 'Approved',
                                            'rejected' => 'Rejected',
                                            default => 'Suspended',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} text-black font-bold px-3 py-2 text-xs uppercase tracking-tighter">
                                        {{ $statusLabel }}
                                    </span>
                                    
                                    @if($review->status === 'rejected' && $review->rejection_reason)
                                        <div class="text-[10px] text-red-400 mt-1 max-w-[150px] truncate" title="{{ $review->rejection_reason }}">
                                            Reason: {{ $review->rejection_reason }}
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <div class="flex flex-col gap-2 items-center">
                                        <div class="flex gap-2">
                                            @if($review->status !== 'active')
                                                <button wire:click="approve({{ $review->id }})" 
                                                        class="btn btn-xs btn-success text-black hover:!bg-green-600">
                                                    Approve
                                                </button>
                                            @endif

                                            @if($review->status !== 'rejected' && $rejectingId !== $review->id)
                                                <button wire:click="startRejection({{ $review->id }})" 
                                                        class="btn btn-xs btn-error text-black hover:!bg-red-600">
                                                    Reject
                                                </button>
                                            @endif
                                        </div>

                                        {{-- INLINE REJECTION FORM --}}
                                        @if($rejectingId === $review->id)
                                            <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg w-64 shadow-inner">
                                                <label class="block text-xs font-bold text-red-700 mb-1 uppercase">Reason for Rejection:</label>
                                                <textarea 
                                                    wire:model="rejectionReason" 
                                                    class="w-full text-xs border-red-200 rounded focus:ring-red-500 focus:border-red-500 mb-2" 
                                                    placeholder="Type the reason for the citizen..."
                                                    rows="3"
                                                ></textarea>
                                                @error('rejectionReason') <span class="text-[10px] text-red-600 block mb-2 font-bold">{{ $message }}</span> @enderror
                                                
                                                <div class="flex justify-end gap-2">
                                                    <button wire:click="cancelRejection" class="text-[10px] font-bold text-gray-500 hover:text-gray-700">Cancel</button>
                                                    <button 
                                                        wire:click="confirmRejection" 
                                                        class="bg-red-600 hover:bg-red-700 text-black text-[10px] font-bold py-1 px-2 rounded transition"
                                                    >
                                                        Confirm Reject
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-gray-400">No reviews found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
</div>