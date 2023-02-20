<?php

declare(strict_types=1);


namespace App\Services;

use App\Models\Station;

class StationService extends Service
{
    private ExperienceService $experienceService;

    public function __construct(
        Station $model,
        ExperienceService $experienceService
    )
    {
        $this->model = $model;
        $this->experienceService = $experienceService;
    }

    public function getStationById(int $id)
    {
        $station = $this->model->where('id', $id)->first();
        return $this->mapper($station);
    }

    public function findBetween(string $from, string $to)
    {
        $stations = $this->model->where(
            [
                [ 'lat', '>', $from ],
                [ 'lat', '<', $to]
        ]
        )->orWhere(
            [
                [ 'lat', '<', $from ],
                [ 'lat', '>', $to]
            ]
        )->get();

        return $this->prepareStationsList($stations, true);
    }

    public function prepareStationsList( $stations, $withDestination = false )
    {
        $outputArr = [];

        foreach ( $stations as $station ) {
            $outputArr[$station->id] = $this->mapper($station);
        }

        return $outputArr;
    }

    private function mapper( $station, $withDestination = false ): array
    {
        $stationData = [];
        $stationData['id'] = $station['id'];
        $stationData['name'] = $station['name'];
        $stationData['lat'] = $station['lat'];
        $stationData['lng'] = $station['lng'];
        $stationData['point'] = $station['lat'] . ',' . $station['lng'];
        $stationData['possibilities'] = $this->preparePossibilities(json_decode($station['possibilities'], true));
        $stationData['type'] = $station['type'];
        $stationData['experience'] = $this->experienceService->mapper($station->experience);
        $stationData['destination_id'] = $station['destination_id'];
        if ( $withDestination ) {
            $stationData['destination_data'] = $station->destination;
        }

        return  $stationData;
    }

    // Add icon class
    private function preparePossibilities(array $possibilities): array
    {
        $outputArr = [];
        foreach ($possibilities as $possibility) {
            $outputArr[$possibility] = [
                'icon' => config('experience.possibilities')[$possibility]
            ];
        }
        return $outputArr;
    }
}
