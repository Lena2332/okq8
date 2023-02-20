<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CalculateDistanceTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->postJson('api/calculate_stops', [
            'stops' => [
                [
                    'name' => 'halmstad',
                    'rest_time' => 0
                ],
               [
                   'name' => 'Ljungby',
                   'rest_time' => 10
               ],
               [
                   'name' => 'Linköping',
                   'rest_time' => 20
               ],
               [
                   'name' => 'Stockholm',
                   'rest_time' => 40
               ],
               [
                   'name' => 'Åre',
                   'rest_time' => 0
               ]
            ]
        ]);

        $response->assertStatus(200);
    }
}
