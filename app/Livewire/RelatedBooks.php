<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Book;

class RelatedBooks extends Component
{
    public $currentBookId;
    public $relatedBooks = [];

    public function mount($bookId)
    {
        $this->currentBookId = $bookId;
        $this->loadRelated();
    }

    public function loadRelated()
    {
        $book = Book::findOrFail($this->currentBookId);
        $this->relatedBooks = $book->getRelatedBooks(4);
    }

    public function render()
    {
        return view('livewire.related-books');
    }
}
