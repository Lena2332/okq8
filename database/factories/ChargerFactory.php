<?php

namespace Database\Factories;

use App\Models\Charger;
use App\Models\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Charger>
 */
class ChargerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Charger::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $effect = [ '1', '2', '3'];

        $stations = Station::pluck('id');

        return [
            'effect' => $this->faker->randomElement($effect),
            'station_id' => $this->faker->randomElement($stations)
        ];
    }
}
