<?php

declare(strict_types=1);


namespace App\Services;

use App\Models\Experience;

class ExperienceService extends Service
{
    public function __construct(
        Experience $model,
    )
    {
        $this->model = $model;
    }

    public function mapper( $experience ): array
    {
        $outputArr = [];

        if ($experience) {
            $outputArr['id'] = $experience->id;
            $outputArr['name'] = $experience->name;
            $outputArr['image'] = $experience->image;
            $outputArr['type'] = $experience->type;
            $outputArr['icon'] = config('experience.experience')[$experience->type];
            $outputArr['description'] = $experience->description;
            $outputArr['is_flagship'] = $experience->flagship;
        }

        return $outputArr;
    }
}
