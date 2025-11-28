<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithFileUploads;

class BooksTable extends Component
{
  use WithPagination, WithFileUploads;

    public $search = '';
    public $sortField = 'title';
    public $sortDirection = 'asc';
    public $filterPublisher = '';

    protected $paginationTheme = 'tailwind';

    // Campos do livro
    public $bookId;
    public $title;
    public $isbn;
    public $year;
    public $price;
    public $bibliography;
    public $publisher_id;
    public $cover_image;
    public $newCover;
    public $isModalOpen = false; // nome que a view espera

    protected $updatesQueryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'title'],
        'sortDirection' => ['except' => 'asc'],
        'filterPublisher' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterPublisher() { $this->resetPage(); }

    public function sortBy($field)
    {
        $allowedFields = ['title', 'isbn', 'publisher_id', 'year', 'price', 'bibliography', 'cover_image'];
        if (!in_array($field, $allowedFields)) return;

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
        $books = Book::with('publisher')->get();

        // SEARCH
        if (!empty($this->search)) {
            $searchLower = mb_strtolower($this->search);
            $books = $books->filter(fn($book) =>
                str_contains(mb_strtolower($book->title ?? ''), $searchLower) ||
                str_contains(mb_strtolower($book->isbn ?? ''), $searchLower) ||
                str_contains(mb_strtolower($book->publisher->name ?? ''), $searchLower)
            );
        }

        // FILTER
        if ($this->filterPublisher) {
            $books = $books->where('publisher_id', $this->filterPublisher);
        }

        // SORT
        $books = $books->sortBy(function ($book) {
            switch ($this->sortField) {
                case 'publisher_id':
                    return strtolower($book->publisher->name ?? '');
                case 'bibliography':
                    return strtolower($book->bibliography ?? '');
                case 'cover_image':
                    return strtolower($book->cover_image ?? '');
                default:
                    return strtolower($book->{$this->sortField} ?? '');
            }
        });

        if ($this->sortDirection === 'desc') {
            $books = $books->reverse();
        }

        // PAGINATION
        $perPage = 10;
        $currentPage = $this->page ?? 1;
        $booksPaginated = $books->slice(($currentPage - 1) * $perPage, $perPage)->values();

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
