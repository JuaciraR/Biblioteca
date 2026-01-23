<?php

namespace Database\Factories;

use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'isbn' => $this->faker->isbn13(),
            'publisher_id' => Publisher::factory(), // Cria um editor automaticamente
            'year' => $this->faker->year(),
            'price' => $this->faker->randomFloat(2, 10, 50),
            'stock' => 10, // Define um stock padrão para os testes passarem
            'bibliography' => $this->faker->paragraph(),
        ];
    }
}