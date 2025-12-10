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
         public function editBook($id)
    {
        if (Auth::user()->role !== 'Admin') abort(403);
        $book = Book::findOrFail($id);
        
      
        $this->publisher_id = $book->publisher_id; 
        
        // 1. Carrega todos os dados do livro para as propriedades públicas do Livewire
        $this->fill($book->toArray()); 
        
        // 2. Garante o publisher_id após o fill (redundância segura)
        $this->publisher_id = $book->publisher_id; 
        
        $this->bookId = $book->id;
        $this->cover_image = $book->cover_image; 
        
        $this->isModalOpen = true;
    }


       public function render()
    {
        // 1. Inicia a Query com Eager Loading (essencial para dados cifrados)
        $booksQuery = Book::with('publisher');
        
        // 2. Aplicar Filtro de Editora (na Query Builder)
        if ($this->filterPublisher) {
            $booksQuery->where('publisher_id', (int) $this->filterPublisher);
        }
        
        // --- CHAVE 1: BUSCAR TODOS OS DADOS COM EAGER LOADING ---
        $books = $booksQuery->get();

        // 3. Aplicar Pesquisa na Collection (Para dados decifrados)
        if (!empty($this->search)) {
            $searchLower = mb_strtolower($this->search);
            
            $books = $books->filter(fn($book) =>
                str_contains(mb_strtolower($book->title ?? ''), $searchLower) ||
                str_contains(mb_strtolower($book->isbn ?? ''), $searchLower) ||
                // Acesso seguro garantido pelo optional()
                str_contains(mb_strtolower(optional($book->publisher)->name ?? ''), $searchLower)
            );
        }

        // 4. Ordenação (Aplicada na Collection, usando dados decifrados)
        $books = $books->sortBy(function ($book) {
            switch ($this->sortField) {
                 case 'publisher_id':
                     // Acessa o nome da editora de forma segura para ordenação
                     return strtolower(optional($book->publisher)->name ?? ''); 
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

        // 5. Paginação Manual
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


  
    public function createBook()
    {
        if (Auth::user()->role !== 'Admin') abort(403);
        $this->resetFields();
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
