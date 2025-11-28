<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BooksExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // Retorna os dados
    public function collection()
    {
        return Book::with('publisher', 'authors')->get()->map(function($book) {
            return [
                'Title' => $book->title,
                'ISBN' => $book->isbn,
                'Year' => $book->year,
                'Price' => $book->price,
                'Publisher' => $book->publisher?->name,
                'Authors' => $book->authors->pluck('name')->implode(', '),
                'Bibliography' => $book->bibliography,
                'Cover Image' => $book->cover_image,
            ];
        });
    }

    // Cabeçalhos da tabela
    public function headings(): array
    {
        return ['Title', 'ISBN', 'Year', 'Price', 'Publisher', 'Authors', 'Bibliography', 'Cover Image'];
    }
}
