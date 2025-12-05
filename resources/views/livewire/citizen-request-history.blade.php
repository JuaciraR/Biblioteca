<div class="mt-10">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="col-span-1">
            <h3 class="text-lg font-medium text-gray-900">{{ __('My Request History') }}</h3>
            <p class="mt-1 text-sm text-gray-600">
                {{ __('View all past and current book requests you have made.') }}
            </p>
        </div>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="overflow-x-auto shadow rounded-lg bg-white">
                @if ($requests->isEmpty())
                    <div class="p-4 text-center text-gray-500">{{ __('You have not made any book requests yet.') }}</div>
                @else
                    <table class="table w-full">
                        <thead class="text-gray-700 bg-gray-100">
                            <tr>
                                <th class="p-3">{{ __('Book') }}</th>
                                <th class="p-3">{{ __('Status') }}</th>
                                <th class="p-3">{{ __('Requested On') }}</th>
                                <th class="p-3">{{ __('Due Date') }}</th>
                                <th class="p-3">{{ __('Received On') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $req)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 font-medium">{{ $req->book->title }}</td>
                                    <td class="p-3">
                                        @php
                                            $status_class = match($req->status) {
                                                'Approved' => 'badge-success',
                                                'Rejected' => 'badge-error',
                                                'Received' => 'badge-info',
                                                'Pending' => 'badge-warning',
                                                default => 'badge-neutral',
                                            };
                                        @endphp
                                        <span class="badge {{ $status_class }} text-white font-bold">{{ $req->status }}</span>
                                    </td>
                                    <td class="p-3">{{ $req->requested_at?->format('Y-m-d') ?? '-' }}</td>
                                    <td class="p-3">{{ $req->due_date?->format('Y-m-d') ?? '-' }}</td>
                                    <td class="p-3">
                                        @if($req->received_at)
                                            {{ $req->received_at->format('Y-m-d') }}
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>