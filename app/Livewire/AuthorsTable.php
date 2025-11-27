<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Author;
use Illuminate\Pagination\LengthAwarePaginator;

class AuthorsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
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
        // 1. Buscar todos os autores
        $authors = Author::all();

        // 2. Filtrar por pesquisa
        if ($this->search) {
            $searchLower = mb_strtolower($this->search);
            $authors = $authors->filter(fn($a) =>
                str_contains(mb_strtolower($a->name), $searchLower)
            );
        }

        // 3. Ordenar pelo campo selecionado
        $authors = $authors->sortBy(fn($a) => strtolower($a->{$this->sortField}));

        if ($this->sortDirection === 'desc') {
            $authors = $authors->reverse();
        }

        // 4. Paginação manual
        $perPage = 10;
        $currentPage = $this->page ?? 1;

        $authorsPaginated = $authors
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values();

        return view('livewire.authors-table', [
            'authors' => new LengthAwarePaginator(
                $authorsPaginated,
                $authors->count(),
                $perPage,
                $currentPage,
                [
                    'path' => request()->url(),
                    'query' => request()->query()
                ]
            )
        ]);
    }
}
