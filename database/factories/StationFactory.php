<?php

namespace Database\Factories;

use App\Models\Destination;
use App\Models\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Station>
 */
class StationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Station::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $possibilitiesArr = [
            ['toilet'],
            ['eat', 'toilet'],
            ['children']
        ];

        $types = [
            'Pit stop', 'Experience hub', 'Stay and eat'
        ];

        $destinations = Destination::pluck('id');

        return [
            'name' => $this->faker->city,
            'lat' => $this->faker->latitude(-90, 90),
            'lng' => $this->faker->longitude(-180,180),
            'possibilities' => json_encode($this->faker->randomElement($possibilitiesArr)),
            'type' => $this->faker->randomElement($types),
            'destination_id' => $this->faker->randomElement($destinations)
        ];
    }
}
