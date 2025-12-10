<div class="p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">
        {{ $isAdmin ? 'Gestão de Requisições da Biblioteca' : 'Minhas Requisições' }}
    </h1>
    <p class="text-black-600 mb-6">
        @if($isAdmin)
            Painel de Admin para aprovar, rejeitar ou confirmar a devolução de livros.
        @else
            Aqui está o histórico e o status de todos os seus pedidos de livros.
        @endif
    </p>

    {{-- BLOCO DE ALERTA (Usa variáveis do componente PHP) --}}
    @if ($message)
        <div class="alert shadow-lg mb-4 
            @if ($messageType === 'success') alert-success
            @elseif ($messageType === 'error') alert-error
            @elseif ($messageType === 'warning') alert-warning
            @endif"
        >
            @if ($messageType === 'success')
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            @elseif ($messageType === 'error')
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            @elseif ($messageType === 'warning')
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.772-1.333-2.688-1.333-3.46 0L3.3 16c-.772 1.333.192 3 1.732 3z" /></svg>
            @endif
            <span>{{ $message }}</span>
        </div>
    @endif

    {{-- CARTÕES DE MÉTRICAS (Visível SOMENTE para Admin) --}}
    @if($isAdmin)
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
            {{-- Métrica 1: Requisições Pendentes --}}
            <div class="stat shadow bg-black border border-yellow-300">
                <div class="stat-figure text-secondary"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current text-yellow-500"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                <div class="stat-title">Requisições Pendentes</div>
                <div class="stat-value text-black-600">{{ $pendingRequestsCount }}</div>
                <div class="stat-desc">Aguardando aprovação</div>
            </div>
            
            {{-- Métrica 2: Ativas --}}
             <div class="stat shadow bg-black">
                <div class="stat-figure text-secondary"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current text-blue-500"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V7M4 7h16M4 7l-2 2m2-2l2-2m12 2l2-2m-2 2l-2-2"></path></svg></div>
                <div class="stat-title">Requisições Ativas</div>
                <div class="stat-value text-black-600">{{ $activeRequestsCount }}</div>
                <div class="stat-desc">Pendentes e Aprovadas</div>
            </div>
            
             {{-- Métrica 3: 30 Dias --}}
             <div class="stat shadow bg-black">
                <div class="stat-figure text-secondary"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current text-purple-500"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                <div class="stat-title">Últimos 30 Dias</div>
                <div class="stat-value">{{ $last30DaysRequestsCount }}</div>
                <div class="stat-desc">Novos Pedidos</div>
            </div>
            
             {{-- Métrica 4: Devolvidos Hoje --}}
             <div class="stat shadow bg-black border border-black-300">
                <div class="stat-figure text-secondary"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current text-green-500"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                <div class="stat-title">Livros Devolvidos Hoje</div>
                <div class="stat-value text-black-600">{{ $deliveredTodayCount }}</div>
                <div class="stat-desc">Confirmação de Receção</div>
            </div>
        </div>
    @endif

    {{-- TABELA DE REQUISIÇÕES --}}
    <div class="overflow-x-auto shadow-lg rounded-xl bg-black">
        <table class="table w-full">
            <thead class="text-black-700 bg-black-100">
                <tr>
                    <th class="p-3">Nº</th>
                    @if($isAdmin)
                        <th class="p-3">Utilizador</th>
                    @endif
                    <th class="p-3">Livro</th>
                    <th class="p-3">Estado</th>
                    <th class="p-3">Requisitado Em</th>
                    <th class="p-3">Data Limite</th>
                    <th class="p-3">Devolvido Em</th>
                    @if($isAdmin)
                        <th class="p-3 text-center">Ações</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $request)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3">#{{ $request->request_number }}</td>
                        @if($isAdmin)
                            <td class="p-3">{{ $request->user->name ?? 'N/A' }}</td>
                        @endif
                        <td class="p-3">{{ $request->book->title ?? 'N/A' }}</td>
                        <td class="p-3">
                            @php
                                $status_class = match($request->status) {
                                    'Approved' => 'badge-success',
                                    'Rejected' => 'badge-error',
                                    'Received' => 'badge-info',
                                    'Pending' => 'badge-warning',
                                    default => 'badge-neutral',
                                };
                            @endphp
                            <span class="badge {{ $status_class }} text-black font-bold">{{ $request->status }}</span>
                        </td>
                        <td class="p-3">{{ $request->requested_at->format('Y-m-d') }}</td>
                        <td class="p-3 font-semibold text-red-600">
                             {{ $request->due_date->format('Y-m-d') }}
                        </td>
                        <td class="p-3">{{ $request->received_at?->format('Y-m-d') ?? 'Pendente' }}</td>
                        @if($isAdmin)
                            <td class="p-3 flex justify-center space-x-2">
                                @if ($request->status === 'Pending')
                                    {{-- APROVAR (Dispara o e-mail real) --}}
                                    <button wire:click="approveRequest({{ $request->id }})" 
                                            class="btn btn-xs btn-success text-black hover:btn-success/80">
                                        Aprovar
                                    </button>
                                    {{-- REJEITAR --}}
                                    <button wire:click="rejectRequest({{ $request->id }})" 
                                            class="btn btn-xs btn-error text-black hover:btn-error/80">
                                        Rejeitar
                                    </button>
                                @elseif ($request->status === 'Approved')
                                    {{-- CONFIRMAR DEVOLUÇÃO/RECEÇÃO --}}
                                    <button wire:click="confirmReceipt({{ $request->id }})" 
                                            class="btn btn-xs btn-info text-black hover:btn-info/80">
                                        Confirmar Receção
                                    </button>
                                @else
                                    <span class="text-gray-400">Finalizado</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>