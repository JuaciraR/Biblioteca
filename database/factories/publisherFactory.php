<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\publisher>
 */
class publisherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
        'name' => $this->faker->company,
        'logo' => null,
        'key'  => $this->faker->uuid(),// pode gerar fake com $this->faker->imageUrl(100,100)
    ];
    }
}
