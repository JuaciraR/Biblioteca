<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Cria um utilizador automaticamente
            'book_id' => Book::factory(), // Cria um livro automaticamente
            'request_number' => 'REQ-' . strtoupper($this->faker->bothify('??###')),
            'status' => 'Pending',
            'requested_at' => now(),
            'due_date' => now()->addDays(7),
        ];
    }
}