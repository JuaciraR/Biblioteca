<div class="flex flex-col h-full bg-white">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white">
        <div>
            <h1 class="text-lg font-bold text-gray-900">
                {{ $title }} 
            </h1>
            <p class="text-xs text-gray-400">
                @if($room)
                    {{ $room->users->count() }} members in this space
                @else
                    Private conversation
                @endif
            </p>
        </div>
    </div>

    <div id="chat-container" wire:poll.3s class="flex-1 overflow-y-auto p-6 space-y-6 bg-gray-50/30">
        @forelse($messages as $message)
            <div class="flex items-start space-x-3">
                <img src="{{ $message->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($message->user->name) }}" 
                     class="w-9 h-9 rounded-full shadow-sm">
                <div class="flex-1">
                    <div class="flex items-baseline space-x-2">
                        <span class="font-bold text-sm text-gray-900">{{ $message->user->name }}</span>
                        <span class="text-[10px] text-gray-400 font-medium">{{ $message->created_at->format('H:i') }}</span>
                    </div>
                    <div class="text-gray-900 text-sm leading-relaxed mt-1">
                        {{ $message->content }}
                    </div>
                </div>
            </div>
        @empty
            <div class="flex items-center justify-center h-full text-gray-300 italic text-sm">
                No messages yet. Start the conversation!
            </div>
        @endforelse
    </div>

    <div class="p-4 bg-white border-t border-gray-100">
        {{-- .prevent evita que a página faça refresh ao clicar no botão --}}
        <form wire:submit.prevent="sendMessage" class="flex items-center space-x-3">
            <input type="text" 
                   wire:model.live="newMessage" 
                   placeholder="Write something to {{ $title }}..." 
                  class="flex-1 border-gray-300 rounded-xl px-4 py-3 text-gray-900 bg-white focus:ring-1 focus:ring-black outline-none text-sm transition">
            
            <button type="submit" class="bg-black text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-sm">
                Send
            </button>
        </form>
    </div>

    <script>
        function scrollToBottom() {
            const container = document.getElementById('chat-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }
        
        window.onload = scrollToBottom;

        document.addEventListener('livewire:initialized', () => {
            @this.on('message-sent', () => {
                setTimeout(scrollToBottom, 50);
            });
        });
    </script>
</div>