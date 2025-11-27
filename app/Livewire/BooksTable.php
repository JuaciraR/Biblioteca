<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Pagination\LengthAwarePaginator;

class BooksTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'title';
    public $sortDirection = 'asc';
    public $filterPublisher = '';

    protected $paginationTheme = 'tailwind';

    protected $updatesQueryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'title'],
        'sortDirection' => ['except' => 'asc'],
        'filterPublisher' => ['except' => ''],
        'page' => ['except' => 1],
    ];

     public function updatingSearch()
    {
    $this->resetPage();
    }

    public function updatedSearch()
    {
    $this->resetPage();
    }


    public function updatingFilterPublisher()
    {
        $this->resetPage();
    }

  public function sortBy($field)
{
    // Permitir ordenação também por bibliography e cover_image
    $allowedFields = ['title', 'isbn', 'publisher_id', 'year', 'price', 'bibliography', 'cover_image'];

    if (!in_array($field, $allowedFields)) {
        return;
    }

    if ($this->sortField === $field) {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        $this->sortField = $field;
        $this->sortDirection = 'asc';
    }

    $this->resetPage();
}


       public function render()
       {
        // Load all books with publisher relation
        $books = Book::with('publisher')->get();

        // SEARCH (case-insensitive)
      if (!empty($this->search)) {
             $searchLower = mb_strtolower($this->search);

            $books = $books->filter(function ($book) use ($searchLower) {
            $title = mb_strtolower($book->title ?? '');
            $isbn = mb_strtolower($book->isbn ?? '');
            $publisher = mb_strtolower($book->publisher->name ?? '');

        return str_contains($title, $searchLower)
            || str_contains($isbn, $searchLower)
            || str_contains($publisher, $searchLower);
           });
           }


        // FILTER BY PUBLISHER
        if ($this->filterPublisher) {
            $books = $books->where('publisher_id', $this->filterPublisher);
        }

        // SORT
        $books = $books->sortBy(function ($book) {
            return strtolower($book->{$this->sortField});
        });

        if ($this->sortDirection === 'desc') {
            $books = $books->reverse();
        }

        // PAGINATION
        $perPage = 10;
        $currentPage = $this->page ?? 1;

        $booksPaginated = $books->slice(
            ($currentPage - 1) * $perPage,
            $perPage
        )->values();

        return view('livewire.books-table', [
            'books' => new LengthAwarePaginator(
                $booksPaginated,
                $books->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            ),

            'publishers' => Publisher::all(),
        ]);
    }
}
