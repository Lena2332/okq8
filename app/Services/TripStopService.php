<?php

declare(strict_types=1);


namespace App\Services;

use App\Models\TripStop;

class TripStopService extends Service
{
    public function __construct(TripStop $model)
    {
        $this->model = $model;
    }

    public function getDestinations(int $start, int $end): array
    {
        $destinationsIdArr = $this->model
            ->where([
                ['start_destination_id', '=', $start],
                ['stop_destination_id', '<>', $start],
                ['stop_destination_id', '<>', $end],
                ['end_destination_id', '=', $end]
            ])
            ->orderBy('position','asc')
            ->pluck('stop_destination_id');

        return $destinationsIdArr;
    }
}
