<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

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
    public $isModalOpen = false;

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
        $filterId = (int) $this->filterPublisher;

        $books = $books->filter(function ($book) use ($filterId) {
        return optional($book->publisher)->id === $filterId;
        });
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
            'isAdmin' => Auth::user()?->role === 'Admin',
        ]);
    }

    // Somente Admin pode criar/editar/excluir
    public function createBook()
    {
        if (Auth::user()->role !== 'Admin') abort(403);
        $this->resetFields();
        $this->isModalOpen = true;
    }

   public function editBook($id)
{
    if (Auth::user()->role !== 'Admin') abort(403);
    $book = Book::findOrFail($id);
    $this->fill($book->toArray());
    $this->bookId = $book->id;
    $this->cover_image = $book->cover_image; 
    $this->isModalOpen = true;
}


  public function saveBook()
{
    if (Auth::user()->role !== 'Admin') abort(403);

    $data = $this->validate([
        'title' => 'required|string|max:255',
        'isbn' => 'required|string|max:255',
        'year' => 'nullable|numeric',
        'price' => 'nullable|numeric',
        'publisher_id' => 'nullable|exists:publishers,id',
        'bibliography' => 'nullable|string',
        'newCover' => 'nullable|image|max:1024',
    ]);

    // Se houver nova imagem, salva
    if ($this->newCover) {
        $data['cover_image'] = $this->newCover->store('covers', 'public');
    } elseif ($this->bookId) {
        // Se estiver editando e não enviar nova capa, mantém a existente
        $book = Book::find($this->bookId);
        $data['cover_image'] = $book->cover_image;
    }

    Book::updateOrCreate(['id' => $this->bookId], $data);

    $this->isModalOpen = false;
    $this->resetFields();
}

    public function deleteBook($id)
    {
        if (Auth::user()->role !== 'Admin') abort(403);
        Book::findOrFail($id)->delete();
    }

    private function resetFields()
    {
        $this->bookId = null;
        $this->title = '';
        $this->isbn = '';
        $this->year = '';
        $this->price = '';
        $this->publisher_id = '';
        $this->bibliography = '';
        $this->cover_image = '';
        $this->newCover = null;
    }
}
