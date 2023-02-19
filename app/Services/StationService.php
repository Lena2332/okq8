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

    public function mapper( $stations ): array
    {
        $outputArr = [];

        foreach ( $stations as $station ) {
            $outputArr[$station->id]['id'] = $station['id'];
            $outputArr[$station->id]['name'] = $station['name'];
            $outputArr[$station->id]['lat'] = $station['lat'];
            $outputArr[$station->id]['lng'] = $station['lng'];
            $outputArr[$station->id]['possibilities'] = $this->preparePossibilities(json_decode($station['possibilities'], true));
            $outputArr[$station->id]['type'] = $station['type'];
            $outputArr[$station->id]['experience'] = $this->experienceService->mapper($station->experience);
        }

        return $outputArr;
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
