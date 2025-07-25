<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Reservation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'registration_id' => function () {
                return \App\Models\Registration::factory()->create()->id;
            },
            'room_id' => function () {
                return \App\Models\Room::factory()->create()->id;
            },
        ];
    }
}
