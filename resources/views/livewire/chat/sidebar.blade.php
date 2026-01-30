@php /** @var \App\Models\User $currentUser */ @endphp
<div class="w-64 bg-gray-50 h-screen border-r border-gray-200 p-4 flex flex-col">
    {{-- Profile Header --}}
    <div class="flex items-center space-x-3 mb-8">
    @if($currentUser)
        <img src="{{ $currentUser->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($currentUser->name) }}" class="w-10 h-10 rounded-full shadow-sm">
        <div class="flex flex-col">
            <span class="font-bold text-gray-800 text-sm">{{ $currentUser->name }}</span>
            
        </div>
    @endif
</div>

    {{-- Lista de Salas --}}
    <div class="mb-6">
        <h2 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Rooms</h2>
        <ul class="space-y-1">
            @foreach($rooms as $room)
                <li>
                    <a href="{{ route('chat.room', $room->id) }}" class="flex items-center p-2 text-sm text-gray-600 hover:bg-gray-200 rounded-lg transition">
                        <span class="mr-2 text-gray-400">#</span> {{ $room->name }}
                    </a>
                </li>
            @endforeach
        </ul>
        @if($currentUser && $currentUser->role === 'Admin')
            <button wire:click="$set('showModal', true)" class="mt-3 text-[11px] text-blue-600 font-semibold px-2 hover:text-blue-800 tracking-tight transition">
                + Create Room
            </button>
        @endif
    </div>

    {{-- Mensagens Diretas --}}
    <div class="flex-1 overflow-y-auto">
        <h2 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Direct Messages</h2>
        <ul class="space-y-1">
            @foreach($users as $user)
                <li>
                    <a href="{{ route('chat.user', $user->id) }}" class="flex items-center p-2 text-sm text-gray-600 hover:bg-gray-200 rounded-lg transition">
                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" class="w-6 h-6 rounded-full mr-3">
                        <span class="truncate">{{ $user->name }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Modal de Criação de Sala --}}
    @if($showModal)
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white p-6 rounded-2xl w-full max-w-sm shadow-2xl animate-in fade-in zoom-in duration-200">
            <h3 class="font-bold text-lg mb-1 text-gray-900">New Chat Room</h3>
            <p class="text-xs text-gray-500 mb-5">Create a space for your team.</p>
            
            <div class="space-y-4">
                {{-- Nome da Sala --}}
                <div>
                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Room Name</label>
                    <input type="text" 
                           wire:model.live="newRoomName" 
                           placeholder="e.g. Design Team" 
                           class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-xl text-gray-900 bg-white focus:ring-2 focus:ring-black focus:border-black text-sm shadow-sm outline-none">
                    
                    @error('newRoomName') 
                        <span class="text-red-500 text-[10px] font-semibold mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>
                
                {{-- Lista de Membros --}}
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Invite Members</label>
                    <div class="max-h-40 overflow-y-auto mt-1 border border-gray-200 rounded-xl p-2 space-y-1 bg-white">
                        @foreach($users as $u)
                            <label class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg cursor-pointer">
                                <input type="checkbox" wire:model.live="selectedUsers" value="{{ $u->id }}" class="rounded text-black focus:ring-black border-gray-300">
                                <span class="text-sm text-gray-800 font-medium">{{ $u->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('selectedUsers') 
                        <span class="text-red-500 text-[10px] font-semibold mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button wire:click="$set('showModal', false)" class="px-4 py-2 text-sm font-semibold text-gray-500 hover:text-gray-700">Cancel</button>
                <button wire:click="createRoom" class="px-6 py-2 bg-black text-white text-sm font-bold rounded-xl hover:bg-gray-800 transition">
                    Create Space
                </button>
            </div>
        </div>
    </div>
    @endif
</div>