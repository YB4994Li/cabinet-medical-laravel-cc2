<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Consultation générale',
                'Cardiologie',
                'Dermatologie',
                'Pédiatrie',
                'Radiologie',
                'Analyse médicale'
            ]),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 100, 1000),
            'duration' => fake()->randomElement([15, 20, 30, 45, 60]),
        ];
    }
}