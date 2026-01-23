<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use App\Traits\Trackable;

class BookDetail extends Component
{
    use Trackable;
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
     
    /**
     * Handles adding the book to the cart with stock validation.
     */
     public function addToCart()
{
    if (!Auth::check()) {
        return session()->flash('error', 'Please log in to purchase books.');
    }

    // 1. Get or Create Cart
    $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

    // 2. Add or Update Item
    $cartItem = CartItem::where('cart_id', $cart->id)
        ->where('book_id', $this->book->id)
        ->first();

    if ($cartItem) {
        
        $cartItem->increment('quantity');
    } else {
        CartItem::create([
            'cart_id' => $cart->id,
            'book_id' => $this->book->id,
            'quantity' => 1
        ]);
    }

    $this->logAudit(
            'Shopping Cart', 
            $this->book->id, 
            "User added book to cart: {$this->book->title}"
        );
    // 3. Importante: Dispara o evento para atualizar o ícone no menu
    $this->dispatch('cart-updated');

    session()->flash('success', 'Book added to your cart!');
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