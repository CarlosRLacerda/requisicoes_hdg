<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'cod' => (string) fake()->numberBetween(111111, 999999),
            'item' => fake()->word(),
            'unidade' => fake()->word(),
            'qtd' => fake()->numberBetween(0, 100)
        ];
    }
}
