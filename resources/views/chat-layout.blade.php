<x-app-layout>
    <div class="flex h-screen overflow-hidden bg-white">
        <livewire:chat.sidebar />

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-white">
            @if(isset($room))
                {{-- If a room is selected, we show the RoomView --}}
                @livewire('chat.room-view', ['room' => $room], key($room->id))
            @elseif(isset($receiver))
                {{-- Future: Direct Message View --}}
                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                    <p class="text-xl">Direct Conversation with {{ $receiver->name }}</p>
                </div>
            @else
                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center h-full text-gray-400 bg-gray-50/50">
                    <svg class="w-16 h-16 mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-lg font-medium">Select a room or a teammate to start chatting</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>