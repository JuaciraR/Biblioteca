<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Publisher;

class PublishersTable extends Component
{
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $publishers = Publisher::all();

        if ($this->search) {
            $searchLower = mb_strtolower($this->search);
            $publishers = $publishers->filter(fn($p) =>
                str_contains(mb_strtolower($p->name), $searchLower)
            );
        }

        $publishers = $publishers->sortBy(fn($p) => strtolower($p->{$this->sortField} ?? ''));

        return view('livewire.publishers-table', [
            'publishers' => $publishers
        ]);
    }
}
