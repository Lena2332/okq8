<?php

namespace Database\Factories;

use App\Models\TripStop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TripStop>
 */
class TripStopFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TripStop::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'start_destination_id' => $this->faker->numberBetween(1, 8),
            'end_destination_id' => $this->faker->numberBetween(1, 8),
            'stop_destination_id' => $this->faker->numberBetween(1, 8),
            'position' => $this->faker->numberBetween(1, 8)
        ];
    }
}
