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

    public function getAllDestinations(int $start, int $end): iterable
    {
        $startLat = $this->getLat($start);
        $endLat = $this->getLat($end);

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

    private function mapper( $destinations ): array
    {
        $outputArr = [];

        foreach ( $destinations as $destination ) {
            $outputArr[$destination->id]['id'] = $destination->id;
            $outputArr[$destination->id]['name'] = $destination->name;
            $outputArr[$destination->id]['lat'] = $destination->lat;
            $outputArr[$destination->id]['lng'] = $destination->lng;
            $outputArr[$destination->id]['stations'] = $this->stationService->mapper($destination->stations);
        }
        dd($outputArr);
        return $outputArr;
    }
}
