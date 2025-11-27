<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $authors = [
            ['name' => 'J.K. Rowling', 'photo' => 'authors/jk_rowling.jpg'],
            ['name' => 'George R.R. Martin', 'photo' => 'authors/grr_martin.jpg'],
            ['name' => 'Stephen King', 'photo' => 'authors/stephen_king.jpg'],
            ['name' => 'Agatha Christie', 'photo' => 'authors/agatha_christie.jpg'],
            ['name' => 'Isabel Allende', 'photo' => 'authors/isabel_allende.jpg'],
            ['name' => 'Paulo Coelho', 'photo' => 'authors/paulo_coelho.jpg'],
            ['name' => 'Chimamanda Ngozi Adichie', 'photo' => 'authors/chimamanda.jpg'],
            ['name' => 'Khaled Hosseini', 'photo' => 'authors/khaled_hosseini.jpg'],
            ['name' => 'Haruki Murakami', 'photo' => 'authors/murakami.jpg'],
            ['name' => 'Margaret Atwood', 'photo' => 'authors/margaret_atwood.jpg'],
        ];

        foreach ($authors as $a) {
            Author::create($a);
        }
    }
}
