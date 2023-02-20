<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\Destination::truncate();
        \App\Models\Destination::factory()
            ->count(8)
            ->state(new Sequence(
                ['name' => 'Halmstad', 'lat' => '56.6691887', 'lng' => '12.8624076'],
                ['name' => 'Jönköping', 'lat' => '57.756103', 'lng' => '14.0481716'],
                ['name' => 'Linköping', 'lat' => '58.4038594', 'lng' => '15.540734'],
                ['name' => 'Ljungby', 'lat' => '56.8332166', 'lng' => '13.9061711'],
                ['name' => 'Åre', 'lat' => '63.3971653', 'lng' => '13.0617457'],
                ['name' => 'Stockholm', 'lat' => '59.326242', 'lng' => '17.8419703'],
                ['name' => 'Gävle', 'lat' => '60.6877031', 'lng' => '16.9025858'],
                ['name' => 'Sundsvall', 'lat' => '62.3959628', 'lng' => '17.0061553'],
            ))
            ->create();

        \App\Models\TripStop::truncate();
        \App\Models\TripStop::factory()
            ->count(8)
            ->state(new Sequence(
                [
                    'start_destination_id' => 1,
                    'end_destination_id' => 5,
                    'stop_destination_id' => 1,
                    'position' => 1
                    ],
                [
                    'start_destination_id' => 1,
                    'end_destination_id' => 5,
                    'stop_destination_id' => 4,
                    'position' => 2
                ],
                [
                    'start_destination_id' => 1,
                    'end_destination_id' => 5,
                    'stop_destination_id' => 2,
                    'position' => 3
                ],
                [
                    'start_destination_id' => 1,
                    'end_destination_id' => 5,
                    'stop_destination_id' => 3,
                    'position' => 4
                ],
                [
                    'start_destination_id' => 1,
                    'end_destination_id' => 5,
                    'stop_destination_id' => 6,
                    'position' => 5
                ],
                [
                    'start_destination_id' => 1,
                    'end_destination_id' => 5,
                    'stop_destination_id' => 7,
                    'position' => 6
                ],
                [
                    'start_destination_id' => 1,
                    'end_destination_id' => 5,
                    'stop_destination_id' => 8,
                    'position' => 7
                ],
                [
                    'start_destination_id' => 1,
                    'end_destination_id' => 5,
                    'stop_destination_id' => 5,
                    'position' => 8
                ]
            ))
            ->create();

        \App\Models\Station::truncate();
        \App\Models\Station::factory()
            ->count(8)
            ->state(new Sequence(
                ['name' => 'Halmstad', 'destination_id' => 1, 'lat' => '56.6691887', 'lng' => '12.8624076'],
                ['name' => 'Jönköping', 'destination_id' => 2, 'lat' => '57.756103', 'lng' => '14.0481716'],
                ['name' => 'Linköping', 'destination_id' => 3, 'lat' => '58.4038594', 'lng' => '15.540734'],
                ['name' => 'Ljungby', 'destination_id' => 4, 'lat' => '56.8332166', 'lng' => '13.9061711'],
                ['name' => 'Åre', 'destination_id' => 5, 'lat' => '63.3971653', 'lng' => '13.0617457'],
                ['name' => 'Stockholm', 'destination_id' => 6, 'lat' => '59.326242', 'lng' => '17.8419703'],
                ['name' => 'Gävle', 'destination_id' => 7, 'lat' => '60.6877031', 'lng' => '16.9025858'],
                ['name' => 'Sundsvall', 'destination_id' => 8, 'lat' => '62.3959628', 'lng' => '17.0061553'],
            ))->create();

        \App\Models\Experience::truncate();
        \App\Models\Experience::factory()
            ->count(8)
            ->create();

        \App\Models\Charger::truncate();
        \App\Models\Charger::factory()
            ->count(70)
            ->create();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
