<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;

class AuthorBookSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'Harry Potter and the Philosopher\'s Stone' => ['J.K. Rowling'],
            'A Game of Thrones' => ['George R.R. Martin'],
            'The Shining' => ['Stephen King'],
            'Murder on the Orient Express' => ['Agatha Christie'],
            'The Alchemist' => ['Paulo Coelho'],
        ];

        foreach ($map as $title => $authors) {
            $book = Book::where('title', $title)->first();

            if ($book) {
                foreach ($authors as $name) {
                    $author = Author::firstWhere('name', $name);
                    if ($author) {
                        $book->authors()->syncWithoutDetaching([$author->id]);
                    }
                }
            }
        }
    }
}
