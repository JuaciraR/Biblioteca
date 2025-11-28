<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Author;

class AuthorsTable extends Component
{
    public $search = '';
    public $filterLetter = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    // Reinicia ordenação quando muda pesquisa/filtro
    public function updatingSearch()
    {
        $this->resetSort();
    }

    public function updatingFilterLetter()
    {
        $this->resetSort();
    }

    // Função para alterar ordenação
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    private function resetSort()
    {
        $this->sortField = 'name';
        $this->sortDirection = 'asc';
    }

    public function render()
    {
        // 1. Buscar todos os autores
        $authors = Author::all();

        // 2. Filtro pela primeira letra
        if ($this->filterLetter) {
            $authors = $authors->filter(fn($a) =>
                strtolower(substr($a->name, 0, 1)) === strtolower($this->filterLetter)
            );
        }

        // 3. Filtro por pesquisa
        if ($this->search) {
            $searchLower = mb_strtolower($this->search);
            $authors = $authors->filter(fn($a) =>
                str_contains(mb_strtolower($a->name), $searchLower)
            );
        }

        // 4. Ordenação
       $authors = $authors->sortBy(function ($a) {
       $value = $a->{$this->sortField};

       // Evita erros quando ordenar pela foto
      return strtolower($value ?? '');
     });

        return view('livewire.authors-table', [
            'authors' => $authors,
        ]);
    }
}
