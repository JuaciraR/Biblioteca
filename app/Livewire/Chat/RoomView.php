<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use App\Models\ChatRoom;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Traits\Trackable;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class RoomView extends Component
{
   use Trackable;
    public $room = null;      
    public $receiver = null;  
    public $newMessage = '';

    /**
     * O mount agora aceita ou um ID de sala ou um ID de utilizador (DM)
     */
    public function mount($room = null, $user = null)
    {
        if ($room) {
            $this->room = ChatRoom::with('users')->findOrFail($room);
        } elseif ($user) {
            $this->receiver = User::findOrFail($user);
        }
    }

    public function sendMessage()
{
    $this->validate(['newMessage' => 'required']);

    $message = Message::create([
        'user_id' => Auth::id(),
        'content' => $this->newMessage,
        'chat_room_id' => $this->room ? $this->room->id : null,
        'receiver_id' => $this->receiver ? $this->receiver->id : null,
    ]);

   
    $module = $this->room ? 'Chat Room' : 'Direct Message';
    $targetId = $this->room ? $this->room->id : $this->receiver->id;
    
    $this->logAudit($module, $targetId, "Sent message: " . substr($this->newMessage, 0, 20) . "...");

    $this->newMessage = '';
    $this->dispatch('message-sent');
}

    public function render()
    {
        $messages = collect();

        if ($this->room) {
            // Mensagens da Sala
            $messages = Message::where('chat_room_id', $this->room->id)
                ->with('user')
                ->oldest()
                ->get();
        } elseif ($this->receiver) {
            // Mensagens Diretas (Conversa entre dois utilizadores)
            $messages = Message::where(function($query) {
                $query->where('user_id', Auth::id())->where('receiver_id', $this->receiver->id);
            })->orWhere(function($query) {
                $query->where('user_id', $this->receiver->id)->where('receiver_id', Auth::id());
            })->with('user')->oldest()->get();
        }

        return view('livewire.chat.room-view', [
            'messages' => $messages,
            'title' => $this->room ? "# " . $this->room->name : $this->receiver->name
            
        ]);

        
    }
}