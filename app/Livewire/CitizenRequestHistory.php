<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Request;
use Illuminate\Support\Facades\Auth;

class CitizenRequestHistory extends Component
{
    public $userId;
    public $user;

    public function mount()
    {
        // Pega o ID do usuário logado para carregar o histórico
        $this->userId = Auth::id();
        $this->user = Auth::user();
    }

    public function render()
    {
        // Regra de Negócio: Cidadão só pode ver o seu próprio histórico.
        if (!$this->user) abort(404);

        // Carrega todas as requisições, ativas e passadas, do utilizador
        $requests = Request::with('book')
                           ->where('user_id', $this->userId)
                           ->orderByDesc('requested_at')
                           ->get();

        return view('livewire.citizen-request-history', [
            'requests' => $requests,
        ]);
    }
}