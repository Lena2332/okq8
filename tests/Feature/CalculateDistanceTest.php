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
                    'id' => '1',
                    'rest_time' => 0
                ],
                [
                   'id' => '2',
                   'rest_time' => 20
               ],
               [
                   'id' => '3',
                   'rest_time' => 10
               ],
               [
                   'id' => '4',
                   'rest_time' => 40
               ],
               [
                   'id' => '5',
                   'rest_time' => 0
               ]
            ]
        ]);

        $response->assertStatus(200);
    }
}
