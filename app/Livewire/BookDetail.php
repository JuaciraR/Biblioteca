<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class BookDetail extends Component
{
    public Book $book;
    public $isAdmin;
    
    // Variáveis de estado para mensagens
    public $flashMessage = null;
    public $flashMessageType = null;

    public function mount(Book $book)
    {
        $this->book = $book->load('publisher');
        $this->isAdmin = Auth::check() && Auth::user()->role === 'Admin';
    }

    // Este método é chamado para garantir que o componente atualize após a ação do botão
    public function hydrate()
    {
        // Se houver uma mensagem flash na sessão (disparada pelo BookRequestButton),
        // puxamos para as propriedades públicas para que o HTML a exiba.
        if (session()->has('success')) {
            $this->flashMessageType = 'success';
            $this->flashMessage = session('success');
        } elseif (session()->has('error')) {
            $this->flashMessageType = 'error';
            $this->flashMessage = session('error');
        }
    }
    
 

    /**
     * Devolve APENAS a view Blade.
     */
    public function render()
    {
        $requestsQuery = $this->book->requests()->with('user')
                                ->orderByDesc('requested_at');

        // Filtro para Cidadão
        if (Auth::check() && Auth::user()->role === 'Cidadao' && !$this->isAdmin) {
             $requestsQuery->where('user_id', Auth::id());
        }
        
        $requests = $requestsQuery->get();
        $isAvailable = $this->book->isAvailableForRequest();

        return view('livewire.book-detail', [
            'requests' => $requests,
            'isAvailable' => $isAvailable,
        ]);
    }
}