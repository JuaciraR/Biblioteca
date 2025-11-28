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
                'cover_image' => 'https://upload.wikimedia.org/wikipedia/en/6/6b/Harry_Potter_and_the_Philosopher%27s_Stone_Book_Cover.jpg',
            ],
            [
                'title' => 'A Game of Thrones',
                'isbn' => '9780553103540',
                'year' => 1996,
                'price' => 34.99,
                'publisher' => 'HarperCollins',
                'bibliography' => 'First book in A Song of Ice and Fire.',
                'cover_image' => 'https://upload.wikimedia.org/wikipedia/en/9/93/AGameOfThrones.jpg',
            ],
            [
                'title' => 'The Shining',
                'isbn' => '9780385121675',
                'year' => 1977,
                'price' => 24.50,
                'publisher' => 'Macmillan Publishers',
                'bibliography' => 'Horror novel set in the Overlook Hotel.',
                'cover_image' => 'https://upload.wikimedia.org/wikipedia/commons/0/09/The_Shining_%281977%29_front_cover%2C_first_edition.jpg',
            ],
            [
                'title' => 'Murder on the Orient Express',
                'isbn' => '9780007119318',
                'year' => 1934,
                'price' => 19.99,
                'publisher' => 'Simon & Schuster',
                'bibliography' => 'Detective novel featuring Hercule Poirot.',
                'cover_image' => 'https://th.bing.com/th/id/R.1458bec4f6ae604ccf6b36f6ac4a0777?rik=fZi2dV1Hghatzw&pid=ImgRaw&r=0',
            ],
            [
                'title' => 'The Alchemist',
                'isbn' => '9780061122415',
                'year' => 1988,
                'price' => 15.00,
                'publisher' => 'Hachette Livre',
                'bibliography' => 'Novel by Paulo Coelho about a shepherd\'s journey.',
                'cover_image' => 'https://tse3.mm.bing.net/th/id/OIP.XaCD-UkLKR3ML-pkarM1GQHaJ4?rs=1&pid=ImgDetMain&o=7&rm=3',
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
