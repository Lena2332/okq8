<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Request;


class BookingService extends Service
{
    // kWh
    const CAPACITY = 54;

    // Wh/km
    const SPENT = 177;

    private StationService $stationService;

    public function __construct( StationService $stationService )
    {
        $this->stationService = $stationService;
    }

    /**
     * @param array $stopsArr
     * @return array
     * [ 'id'=>*,
     *   'rest_time'=>*,
     *   'recommended_time'=>*,
     *   'capacity_start'=>*,
     *   'capacity_start_percent'=>*,
     *   'capacity_end'=>*,
     *   'capacity_end_percent'=>*,
     *   'charging_capacity'=>,
     *   'recommended_stops'=>[],
     *   'available_next'=>true/false
     * ]
     */
    public function getAproximateStops(array $stopsArr): array
    {
        $outputArr = [];
        $stationData = [];
        foreach ( $stopsArr as $k => $stop ) {
            $stationData[$k] = $this->stationService->getStationById( (int) $stop['id'] );
            $stationData[$k]['rest'] = $stop['rest_time'];
        }

        $startCapacity = $this::CAPACITY;
        foreach ( $stationData as $k => $station ) {
            $start = $station['point'];
            $outputArr[$k]['id'] = $station['id'];
            $outputArr[$k]['rest_time'] = $station['rest'];

            // if we have next destination
            if ( isset($stationData[$k+1]) ) {
                $end = $stationData[$k+1]['point'];
                $nextDistance = $this->getDistance($start, $end);

                $outputArr[$k]['capacity_start'] = $startCapacity;
                $outputArr[$k]['capacity_start_percent'] = $this->capacityToPercent($startCapacity);

                //Calculate how many capacity without charging
                $endCapacity = $this->getEndCapacity($startCapacity, $nextDistance);

                //Calculate how many capacity with charging
                $chargingCapacity = $this->getChargingCapacity($station['rest'], 22);
                $outputArr[$k]['charging_capacity'] = $chargingCapacity;
                $finalCapacity = $endCapacity + $chargingCapacity;
                $finalCapacity = ($finalCapacity > $this::CAPACITY) ? $this::CAPACITY : $finalCapacity;

                $finalCapacityPercent = $this->capacityToPercent($finalCapacity);

                $outputArr[$k]['recommended_stops'] = [];
                $outputArr[$k]['available_next'] = true;
                if ( $finalCapacity <= 0) {

                    $finalCapacity = 0;
                    $finalCapacityPercent = 0;

                    // Should suggest new destination between two stations
                    $sugestedStations = $this->stationService->findBetween( $station['lat'], $stationData[$k+1]['lat'] );

                    $outputArr[$k]['recommended_stops'] = $sugestedStations;

                    if ( !count($sugestedStations) ) {
                        $outputArr[$k]['available_next'] = false;
                    }
                }
                $startCapacity = $finalCapacity;
                $outputArr[$k]['capacity_end'] = $finalCapacity;
                $outputArr[$k]['capacity_end_percent'] = $finalCapacityPercent;
            }
        }

        return $outputArr;
    }

    private function capacityToPercent( $capacity ): int
    {
        $percentCap = ($capacity * 100) / $this::CAPACITY;
        return intval($percentCap);
    }

    private function getChargingCapacity( $time, $chargerType = 22 ): int
    {
        $chargerCap = ( $time / 60 ) * $chargerType * 2;

        return intval($chargerCap);
    }

    private function getDistance( string $from, string $to )
    {
        $distance = google_distance($from, $to);

        return $distance/1000;
    }

    /**
     * @param $startCapacity
     * @param $destination
     * @return end capacity before charging
     */
    private function getEndCapacity ( $startCapacity, $destination ): int
    {
        $wastedCapacity = ($this::SPENT * $destination) / 1000;
        return intval(round($startCapacity - $wastedCapacity));
    }


}
