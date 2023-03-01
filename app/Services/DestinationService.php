<?php

declare(strict_types=1);


namespace App\Services;

use App\Models\Destination;

class DestinationService extends Service
{
    private StationService $stationService;

    public function __construct(
        Destination $model,
        StationService $stationService
    )
    {
        $this->model = $model;
        $this->stationService = $stationService;
    }

    public function getAllDestinations(string $start, string $end): iterable
    {
        $startLat = $this->getLatByName($start);
        $endLat = $this->getLatByName($end);

        // If car goes towards Åre
        $orderType = 'asc';
        $filterArr = [
            ['lat', '>', $startLat],
            ['lat', '<', $endLat]
        ];

        // If car goes towards Halmstad
        if( $startLat > $endLat ) {
            $orderType = 'desc';
            $filterArr = [
                ['lat', '<', $startLat],
                ['lat', '>', $endLat]
            ];
        }

        $destinations = $this->model::where($filterArr)->orderBy('lat', $orderType)->get();

        $destinationsData = $this->mapper($destinations);

        return $destinationsData;
    }

    private function getLat(int $id)
    {
        $model = $this->model::select('lat')->where('id', $id)->first();
        return $model->lat;
    }

    private function getLatByName(string $name)
    {
        $model = $this->model::select('lat')->where('name', 'LIKE', '%'. $name .'%')->first();
        return $model->lat;
    }

    private function mapper( $destinations ): array
    {
        $outputArr = [];

        foreach ( $destinations as $destination ) {
            $stationData = $this->stationService->prepareStation($destination->stations->first());
            $outputArr[]['stations'] = [
                'id' => $destination->id,
                'name' => $destination->name,
                'lat' => $destination->lat,
                'lng' => $destination->lng,
                'point' => $stationData['point'],
                'possibilities' => $stationData['possibilities'],
                'type' => $stationData['type'],
                'experience' => $stationData['experience'],
                'destination_id' => $stationData['destination_id'],
            ];
        }

        return $outputArr;
    }
}
