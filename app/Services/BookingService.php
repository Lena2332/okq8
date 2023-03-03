<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Request;


class BookingService extends Service
{
    // capacity of our engine (kWh)
    const CAPACITY = 54;

    // Wh/km
    const SPENT = 177;

    // How much charge should be left after arrival
    const RESERVE = 4;

    // Charging type
    const CHARGING = 22;

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
            $stationData[$k] = $this->stationService->getStationByName( $stop['name'] );
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
                $outputArr[$k]['distance'] = $nextDistance;
                $outputArr[$k]['destination_name'] = $station['name'];
                $outputArr[$k]['capacity_start'] = $startCapacity;
                $outputArr[$k]['capacity_start_percent'] = $this->capacityToPercent($startCapacity);

                //Calculate how much charge is needed for this distance
                $neededCapacity = $this->getNeededCapacity($nextDistance);
                $neededCapacityInPercent = $this->capacityToPercent($neededCapacity);
                $outputArr[$k]['needed_capacity'] = $neededCapacity;
                $outputArr[$k]['needed_capacity_percent'] = $neededCapacityInPercent;

                //Calculate how much charge do we get during stopping
                $chargingCapacity = $this->getChargingCapacity($station['rest']);
                $outputArr[$k]['charging_capacity'] = $chargingCapacity;

                $chargeBeforeNextTrip = $startCapacity + $chargingCapacity;
                $chargeBeforeNextTripInPercent = $this->capacityToPercent($chargeBeforeNextTrip);
                $outputArr[$k]['capacity_before_trip'] = $chargeBeforeNextTrip;
                $outputArr[$k]['capacity_before_trip_percent'] = $chargeBeforeNextTripInPercent;

                $outputArr[$k]['recommended_stops'] = [];
                $outputArr[$k]['available_next'] = true;

                $capacityDifference = $chargeBeforeNextTripInPercent - $neededCapacityInPercent;

                if ( $capacityDifference <= $this::RESERVE ) {
                    //Calculate recommended time of rest
                    $needToCharge = $neededCapacity + $this::RESERVE - $startCapacity;
                    $recommendedTime = $this->calculateRecommendedStopTime($needToCharge);
                    $outputArr[$k]['recommended_time'] = $recommendedTime;

                    // Should suggest new destination between two stations
                    $sugestedStations = $this->stationService->findBetween( $station['lat'], $stationData[$k+1]['lat'] );

                    $outputArr[$k]['recommended_stops'] = $sugestedStations;

                    if ( !count($sugestedStations) ) {
                        $outputArr[$k]['available_next'] = false;
                    }

                    $startCapacity = $this->getChargingCapacity($recommendedTime) + $this::RESERVE;
                    $startCapacityInPercent = $this->capacityToPercent($startCapacity);
                } else {

                    $startCapacity = $chargeBeforeNextTrip - $neededCapacity;
                    $startCapacityInPercent = $this->capacityToPercent($startCapacity);

                }

                $outputArr[$k]['capacity_end'] = $startCapacity;
                $outputArr[$k]['capacity_end_percent'] = $startCapacityInPercent;
            }
        }

        return $outputArr;
    }

    private function capacityToPercent( $capacity ): int
    {
        $percentCap = ($capacity * 100) / $this::CAPACITY;
        return intval($percentCap);
    }

    private function getChargingCapacity( $time ): int
    {
        $chargerCap = ( $time / 60 ) * $this::CHARGING;
        return intval($chargerCap);
    }

    /**
     * @param string $from (coordinates or address)
     * @param string $to (coordinates or address)
     * @return int KM
     */
    private function getDistance( string $from, string $to ): int
    {
        $distance = google_distance($from, $to);
        return intval(round($distance/1000));
    }

    /**
     * @param $startCapacity
     * @param $destination
     * @return energy is needed for distance
     */
    private function getNeededCapacity ( $destination ): int
    {
        $wastedCapacity = ($this::SPENT * $destination) / 1000;
        return intval(round($wastedCapacity));
    }

    /**
     * @param int $needToCharge
     * @return int time in minutes
     */
    private function calculateRecommendedStopTime(int $needToCharge): int
    {
        $time = ( $needToCharge/$this::CHARGING ) * 60;
        return intval($time);
    }
}
