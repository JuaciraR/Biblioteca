<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Publisher;

class BooksAndPublishersSeeder extends Seeder
{
    public function run()
    {
        // Create publishers
        $publisher1 = Publisher::create(['name' => 'Tech Books Publishing']);
        $publisher2 = Publisher::create(['name' => 'PHP Press']);

        // Create books
        Book::create([
            'title' => 'Learning Laravel 10', // automatically encrypted
            'isbn' => '978-1234567890',      // automatically encrypted
            'year' => 2025,
            'price' => 59.90,                 // decimal, not encrypted
            'publisher_id' => $publisher1->id,
        ]);

        Book::create([
            'title' => 'Mastering PHP',
            'isbn' => '978-0987654321',
            'year' => 2024,
            'price' => 49.90,
            'publisher_id' => $publisher1->id,
        ]);

        Book::create([
            'title' => 'DaisyUI in Practice',
            'isbn' => '978-1122334455',
            'year' => 2025,
            'price' => 39.90,
            'publisher_id' => $publisher2->id,
        ]);
    }
}
