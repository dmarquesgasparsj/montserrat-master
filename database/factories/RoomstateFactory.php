<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoomstateFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $statuses = ['available', 'occupied', 'maintenance', 'cleaning'];

        return [
            'room_id' => function () {
                return \App\Models\Room::factory()->create()->id;
            },
            'statechange_at' => $this->faker->dateTime('now'),
            'statusfrom' => $this->faker->randomElement($statuses),
            'statusto' => $this->faker->randomElement($statuses),
        ];
    }
}
