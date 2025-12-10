<div class="py-12" 
    x-data="{ showNotification: false, alertMessage: '', alertType: '' }"
    x-init="
        // OUVE O EVENTO 'request-notification' DISPARADO PELO PHP
        window.addEventListener('request-notification', (e) => {
            alertMessage = e.detail.message;
            alertType = e.detail.type; 
            showNotification = true;
            setTimeout(() => { showNotification = false }, 5000); // Esconde após 5s
        });
    "
>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 space-y-8">
            
            {{-- NOTIFICAÇÃO FLUTUANTE (SweetAlert-style Pop-up) --}}
            <div 
                x-show="showNotification" 
                x-cloak 
                x-transition:enter="transition ease-out duration-300" 
                x-transition:leave="transition ease-in duration-300" 
                class="fixed bottom-0 right-0 p-4 z-50 transform"
            >
                <div :class="{ 'bg-green-500 text-black': alertType === 'success', 'bg-red-500 text-black': alertType === 'error' }" class="p-4 rounded-lg shadow-xl min-w-[300px]">
                    <p x-text="alertMessage" class="font-semibold"></p>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-8">
                
                {{-- COVER IMAGE --}}
                <div class="md:w-1/3 flex flex-col items-center space-y-4">
                    <img src="{{ $book->cover_image ?? 'https://placehold.co/400x600/E5E7EB/1F2937?text=No+Cover' }}" 
                         alt="{{ $book->title }} cover" 
                         class="rounded-lg shadow-md w-48 h-64 object-cover">
                    
                    {{-- Botão de Requisição --}}
                    @if (Auth::check() && (Auth::user()->role === 'Cidadao' || $isAdmin))
                        <livewire:book-request-button :book="$book" :key="'request-'.$book->id" />
                    @endif

                    {{-- Status de Disponibilidade (Visualização) --}}
                    @php
                        $status = $isAvailable ? 'Available' : 'In Request';
                        $status_class = $isAvailable ? 'bg-green-500' : 'bg-red-500';
                    @endphp
                    <span class="px-4 py-1 text-sm font-bold text-white rounded-full {{ $status_class }}">
                        {{ __($status) }}
                    </span>

                </div>
                
                {{-- DETALHES DO LIVRO --}}
                <div class="md:w-2/3 space-y-4">
                    <h1 class="text-3xl font-bold">{{ $book->title }}</h1>
                    <p class="text-lg text-gray-600"><strong>{{ __('ISBN') }}:</strong> {{ $book->isbn }}</p>
                    <p class="text-lg text-gray-600"><strong>{{ __('Publisher') }}:</strong> {{ $book->publisher->name ?? 'N/A' }}</p>
                    <p class="text-lg text-gray-600"><strong>{{ __('Year') }}:</strong> {{ $book->year ?? 'N/A' }}</p>
                    <p class="text-lg text-gray-600"><strong>{{ __('Price') }}:</strong> {{ $book->price ? '€'.number_format($book->price, 2) : 'N/A' }}</p>
                    <div class="border-t pt-4">
                        <h2 class="text-xl font-semibold mb-2">{{ __('Bibliography / Summary') }}</h2>
                        <p class="text-gray-700">{{ $book->bibliography ?? 'No summary provided.' }}</p>
                    </div>
                </div>
            </div>

            {{-- HISTÓRICO DE REQUISIÇÕES DO LIVRO --}}
            <div class="mt-8 border-t pt-8">
                <h2 class="text-2xl font-bold mb-4">{{ __('Request History') }} ({{ $requests->count() }})</h2>
                
                @if (Auth::user()?->role === 'Cidadao')
                    <p class="text-sm text-gray-500 mb-4">{{ __('Only your requests for this book are visible.') }}</p>
                @endif

                @if ($requests->isEmpty())
                    <p class="text-gray-500">{{ __('This book has no request history yet.') }}</p>
                @else
                    <div class="overflow-x-auto shadow rounded-lg">
                        <table class="table w-full">
                            <thead class="text-gray-700 bg-gray-100">
                                <tr>
                                    <th class="p-3">{{ __('User') }}</th>
                                    <th class="p-3">{{ __('Status') }}</th>
                                    <th class="p-3">{{ __('Requested On') }}</th>
                                    <th class="p-3">{{ __('Due Date') }}</th>
                                    <th class="p-3">{{ __('Received On') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requests as $req)
                                    <tr class="hover:bg-gray-50">
                                        <td class="p-3">{{ $req->user->name ?? 'User Deleted' }}</td>
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
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>