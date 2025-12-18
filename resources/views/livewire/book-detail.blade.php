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
     
            {{-- SEÇÃO DE REVIEWS & RATINGS --}}
            <div class="mt-12 border-t pt-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">{{ __('Reviews & Ratings') }}</h2>
                    
                    {{-- Média de Estrelas --}}
                    <div class="flex items-center space-x-2 bg-indigo-50 px-4 py-2 rounded-lg">
                        <span class="text-2xl font-black text-indigo-600">{{ number_format($book->averageRating(), 1) }}</span>
                        <div class="flex text-yellow-400">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $book->averageRating() ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <span class="text-sm text-gray-500">({{ $book->reviews->count() }} reviews)</span>
                    </div>
                </div>

                {{-- Lista de Reviews --}}
                <div class="space-y-6 mb-10">
                    @forelse ($book->reviews as $review)
                        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm transition hover:shadow-md">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $review->user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex text-yellow-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-gray-700 leading-relaxed italic">"{{ $review->comment }}"</p>
                        </div>
                    @empty
                        <div class="text-center py-8 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                            <p class="text-gray-400">{{ __('No reviews yet. Be the first to share your thoughts!') }}</p>
                        </div>
                    @endforelse
                </div>

                {{-- FORMULÁRIO DE REVIEW --}}
                <div class="mt-8 border-t pt-8">
                    @php
                        $isCitizen = Auth::check() && Auth::user()->role === 'Cidadao';
                        $canReview = false; 
                        
                        // Verifica se o utilizador tem alguma requisição com estado 'Received' (Devolvido)
                        if ($isCitizen) {
                            foreach ($requests as $req) {
                                if ($req->status === 'Received') {
                                    $canReview = true;
                                    break;
                                }
                            }
                        }
                    @endphp

                    @if ($canReview)
                        <div class="max-w-xl mx-auto">
                            <livewire:review-form :book-id="$book->id" :key="'review-form-'.$book->id" />
                        </div>
                    @elseif ($isCitizen)
                        <p class="text-gray-500 text-center">{{ __('The review form will become available after you have returned the book.') }}</p>
                    @else
                        <p class="text-gray-500 text-center text-sm italic">{{ __('Reviews are submitted by readers who have borrowed and returned this book.') }}</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>