<div class="w-full">
    {{-- CASO 1: LIVRO DISPONÍVEL --}}
    @if($isAvailable)
        <button wire:click="requestBook" 
                class="btn btn-sm btn-primary w-full text-black font-bold">
            {{ __('Request') }}
        </button>

    {{-- CASO 2: LIVRO INDISPONÍVEL --}}
    @else
        <div class="flex flex-col gap-1">
            <button disabled 
                    class="btn btn-xs bg-gray-200 text-gray-500 cursor-not-allowed border-none">
                {{ __('Unavailable') }}
            </button>

            {{-- LÓGICA DO ALERTA --}}
            @if(Auth::check() && Auth::user()->role === 'Cidadao')
                @if($hasAlert)
                    <span class="text-[9px] text-green-600 font-bold text-center">✓ Alert active</span>
                @else
                    <button wire:click="subscribeToAlert" 
                            class="text-[9px] text-blue-600 underline hover:text-blue-800 font-bold">
                        {{ __('Notify me') }}
                    </button>
                @endif
            @endif
        </div>
    @endif
</div>