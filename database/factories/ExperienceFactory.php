<?php

namespace Database\Factories;

use App\Models\Experience;
use App\Models\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Experience::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $expArr = [
           'movie',
           'children_room',
           'restaurant',
           'hotel'
        ];

        $stations = Station::pluck('id');

        return [
            'name' => $this->faker->sentence(),
            'type' => $this->faker->randomElement($expArr),
            'image' => $this->faker->imageUrl(640, 480, 'animals', true),
            'description' => $this->faker->paragraphs(2, true),
            'flagship' =>  $this->faker->randomElement(['0','1']),
            'station_id' => $this->faker->randomElement($stations)
        ];
    }
}
