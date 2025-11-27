<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Publisher;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'title' => 'Harry Potter',
                'isbn' => '9780747532743',
                'year' => 1997,
                'price' => 29.99,
                'publisher' => 'Penguin Random House',
                'bibliography' => 'First book in the Harry Potter series.',
                'cover_image' => 'covers/harry_potter_1.jpg',
            ],
            [
                'title' => 'A Game of Thrones',
                'isbn' => '9780553103540',
                'year' => 1996,
                'price' => 34.99,
                'publisher' => 'HarperCollins',
                'bibliography' => 'First book in A Song of Ice and Fire.',
                'cover_image' => 'covers/game_of_thrones.jpg',
            ],
            [
                'title' => 'The Shining',
                'isbn' => '9780385121675',
                'year' => 1977,
                'price' => 24.50,
                'publisher' => 'Macmillan Publishers',
                'bibliography' => 'Horror novel set in the Overlook Hotel.',
                'cover_image' => 'covers/the_shining.jpg',
            ],
            [
                'title' => 'Murder on the Orient Express',
                'isbn' => '9780007119318',
                'year' => 1934,
                'price' => 19.99,
                'publisher' => 'Simon & Schuster',
                'bibliography' => 'Detective novel featuring Hercule Poirot.',
                'cover_image' => 'covers/orient_express.jpg',
            ],
            [
                'title' => 'The Alchemist',
                'isbn' => '9780061122415',
                'year' => 1988,
                'price' => 15.00,
                'publisher' => 'Hachette Livre',
                'bibliography' => 'Novel by Paulo Coelho about a shepherd\'s journey.',
                'cover_image' => 'covers/the_alchemist.jpg',
            ],
        ];

        foreach ($books as $b) {
            $publisher = Publisher::firstWhere('name', $b['publisher']);

            Book::create([
                'title' => $b['title'],
                'isbn' => $b['isbn'],
                'year' => $b['year'],
                'price' => $b['price'],
                'publisher_id' => $publisher->id ?? null,
                'bibliography' => $b['bibliography'],
                'cover_image' => $b['cover_image'],
            ]);
        }
    }
}
