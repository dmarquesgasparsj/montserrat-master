<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MealFactory extends Factory
{
    protected $model = \App\Models\Meal::class;

    public function definition(): array
    {
        return [
            'retreat_id' => \App\Models\Retreat::factory(),
            'meal_date' => $this->faker->dateTimeBetween('-1 week', '+1 week'),
            'meal_type' => $this->faker->randomElement(['Breakfast', 'Lunch', 'Dinner']),
            'vegetarian_count' => $this->faker->numberBetween(0, 10),
            'gluten_free_count' => $this->faker->numberBetween(0, 10),
            'dairy_free_count' => $this->faker->numberBetween(0, 10),
        ];
    }
}
