<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Traits\Trackable;

class Sidebar extends Component
{
    use Trackable;

    public $showModal = false;
    public $newRoomName = '';
    public $selectedUsers = [];

    public function render()
    {
        /** @var \App\Models\User|null $currentUser */
        $currentUser = Auth::user();

        // Se não houver utilizador, retornamos uma vista vazia para evitar erros
        if (!$currentUser) {
            return view('livewire.chat.sidebar', ['rooms' => [], 'users' => [], 'currentUser' => null]);
        }

        $rooms = $currentUser->role === 'Admin' 
            ? ChatRoom::with('users')->get() 
            : $currentUser->chatRooms()->with('users')->get();

        $users = User::where('id', '!=', $currentUser->id)->get();

        return view('livewire.chat.sidebar', [
            'rooms' => $rooms,
            'users' => $users,
            'currentUser' => $currentUser
        ]);
    }

   public function createRoom()
{
    if (Auth::user()->role !== 'Admin') return;

    $this->validate([
        'newRoomName' => 'required|min:3',
        'selectedUsers' => 'required|array|min:1'
    ]);

    // Criar a sala
    $room = ChatRoom::create([
        'name' => $this->newRoomName,
        'owner_id' => Auth::id(),
    ]);

    // Anexar utilizadores
    $room->users()->attach($this->selectedUsers);
    $room->users()->attach(Auth::id());

    // Executar log (garanta que o Trait está correto)
    $this->logAudit('Chat', $room->id, "Created chat room: {$this->newRoomName}");

    // Resetar campos e fechar o modal
    $this->reset(['showModal', 'newRoomName', 'selectedUsers']);
    
    // Opcional: Notificar para atualizar a lista sem refresh
    $this->dispatch('room-created'); 
}
}