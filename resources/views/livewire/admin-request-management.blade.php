<div class="p-4 sm:p-6 lg:p-8 bg-white">
    <h1 class="text-4xl font-black text-gray-900 mb-2 uppercase tracking-tighter italic">
        {{ $isAdmin ? 'Library Request Management' : 'My Requests' }}
    </h1>
    <p class="text-gray-900 font-black mb-6 italic uppercase text-xs tracking-wider">
        @if($isAdmin)
            Admin panel to approve, reject, or confirm book returns.
        @else
            Here is the history and status of all your book requests.
        @endif
    </p>

    {{-- HIGH-CONTRAST ALERTS --}}
    @if ($message)
        <div class="p-5 mb-6 border-4 font-black uppercase text-sm tracking-widest shadow-lg rounded-2xl
            @if ($messageType === 'success') bg-green-200 text-green-950 border-green-700
            @elseif ($messageType === 'error') bg-red-200 text-red-950 border-red-700
            @elseif ($messageType === 'warning') bg-amber-200 text-amber-950 border-amber-700
            @endif"
        >
            <div class="flex items-center">
                <i class="fas {{ $messageType === 'success' ? 'fa-check-circle' : ($messageType === 'error' ? 'fa-times-circle' : 'fa-exclamation-triangle') }} mr-3 text-2xl"></i>
                <span>{{ $message }}</span>
            </div>
        </div>
    @endif

    {{-- METRIC CARDS (ADMIN ONLY) --}}
    @if($isAdmin)
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-10">
            @php
                $metrics = [
                    ['title' => 'Pending Requests', 'value' => $pendingRequestsCount, 'color' => 'amber-600', 'icon' => 'fa-clock'],
                    ['title' => 'Active Requests', 'value' => $activeRequestsCount, 'color' => 'blue-700', 'icon' => 'fa-book-reader'],
                    ['title' => 'Last 30 Days', 'value' => $last30DaysRequestsCount, 'color' => 'gray-900', 'icon' => 'fa-calendar-alt'],
                    ['title' => 'Returned Today', 'value' => $deliveredTodayCount, 'color' => 'green-700', 'icon' => 'fa-check-double'],
                ];
            @endphp

            @foreach($metrics as $metric)
                <div class="p-6 bg-white border-4 border-gray-900 rounded-[2rem] shadow-xl">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-gray-900 font-black uppercase text-[10px] tracking-widest">{{ $metric['title'] }}</span>
                        <i class="fas {{ $metric['icon'] }} text-xl text-{{ $metric['color'] }}"></i>
                    </div>
                    <div class="text-4xl font-black text-gray-900 mb-1">{{ $metric['value'] }}</div>
                    <div class="text-[10px] font-bold text-gray-700 uppercase italic">Updated in real-time</div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- HIGH-CONTRAST REQUESTS TABLE --}}
    <div class="overflow-hidden rounded-[2.5rem] border-4 border-gray-900 shadow-2xl bg-white">
        <table class="table w-full border-collapse">
            <thead class="bg-gray-900 border-b-4 border-gray-900">
                <tr class="text-white font-black uppercase text-xs tracking-widest">
                    <th class="p-5 text-center">No.</th>
                    @if($isAdmin) <th class="p-5">User</th> @endif
                    <th class="p-5">Book Title</th>
                    <th class="p-5 text-center">Status</th>
                    <th class="p-5 text-center">Requested</th>
                    <th class="p-5 text-center">Due Date</th>
                    <th class="p-5 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y-4 divide-gray-100">
                @foreach ($requests as $request)
                    <tr class="hover:bg-gray-50 transition-all">
                        <td class="p-5 text-center font-black text-gray-900">#{{ $request->request_number }}</td>
                        @if($isAdmin)
                            <td class="p-5 text-gray-900 font-black italic">{{ $request->user->name ?? 'N/A' }}</td>
                        @endif
                        <td class="p-5 text-gray-900 font-bold text-sm">{{ $request->book->title ?? 'N/A' }}</td>
                        <td class="p-5 text-center">
                            @php
                                $status_style = match($request->status) {
                                    'Approved' => 'bg-green-500 text-white border-green-600',
                                    'Rejected' => 'bg-red-600 text-white border-red-600',
                                    'Received' => 'bg-blue-500 text-white border-blue-600',
                                    'Pending'  => 'bg-amber-500 text-white border-amber-600',
                                    default    => 'bg-gray-600 text-white border-gray-600',
                                };
                            @endphp
                            <span class="border-2 font-black uppercase text-[10px] px-4 py-2 rounded-lg shadow-sm {{ $status_style }}">
                                {{ $request->status }}
                            </span>
                        </td>
                        <td class="p-5 text-center text-gray-900 font-bold text-xs">{{ $request->requested_at->format('Y-m-d') }}</td>
                        <td class="p-5 text-center font-black text-red-700 bg-red-50/30 italic text-sm">
                            {{ optional($request->due_date)->format('Y-m-d') ?? '-' }}

                        </td>
                        <td class="p-5 text-center">
                            <div class="flex justify-center gap-2">
                                @if ($request->status === 'Pending' && $isAdmin)
                                    <button wire:click="approveRequest({{ $request->id }})" class="px-4 py-2 bg-green-600 text-black font-black rounded-xl uppercase text-[10px] border-b-4 border-black active:translate-y-1 transition-all">Approve</button>
                                    <button wire:click="rejectRequest({{ $request->id }})" class="px-4 py-2 bg-red-600 text-black font-black rounded-xl uppercase text-[10px] border-b-4 border-black active:translate-y-1 transition-all">Reject</button>
                                @elseif ($request->status === 'Approved' && $isAdmin)
                                    <button wire:click="confirmReceipt({{ $request->id }})" class="px-6 py-2 bg-blue-600 text-black font-black rounded-xl uppercase text-[10px] border-b-4 border-black active:translate-y-1 transition-all">Confirm Return</button>
                                @else
                                    <span class="text-gray-900 font-black uppercase text-[10px] tracking-widest">Completed</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>