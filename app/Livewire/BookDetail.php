<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class BookDetail extends Component
{
    public Book $book;
    public $isAdmin;
    
    public $relatedBooks = [];
    public $flashMessage = null;
    public $flashMessageType = null;

    public function mount(Book $book)
    {
        $this->book = $book->load('publisher');
        $this->isAdmin = Auth::check() && Auth::user()->role === 'Admin';
        $this->relatedBooks = $this->book->getRelatedBooks(5);
    }

    public function hydrate()
    {
        if (session()->has('success')) {
            $this->flashMessageType = 'success';
            $this->flashMessage = session('success');
        } elseif (session()->has('error')) {
            $this->flashMessageType = 'error';
            $this->flashMessage = session('error');
        }
    }

    public function render()
    {
        $requestsQuery = $this->book->requests()->with('user')
                                ->orderByDesc('requested_at');

        if (Auth::check() && Auth::user()->role === 'Cidadao' && !$this->isAdmin) {
             $requestsQuery->where('user_id', Auth::id());
        }
        
        //apenas as reviews ATIVAS para a listagem pública
        $activeReviews = $this->book->reviews()
            ->where('status', 'active')
            ->with('user')
            ->latest()
            ->get();

        return view('livewire.book-detail', [
            'requests' => $requestsQuery->get(),
            'isAvailable' => $this->book->isAvailableForRequest(),
            'activeReviews' => $activeReviews, // Passamos a variável filtrada
            'averageRating' => $this->book->averageRating(),
            'totalReviews' => $activeReviews->count(), // Contamos apenas as visíveis
        ]);
    }
}