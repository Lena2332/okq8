<?php

namespace App\Http\Controllers;

use App\Services\DestinationService;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    protected DestinationService $service;

    public function __construct(
        DestinationService $service
    )
    {
        $this->service = $service;
    }

    public function getDestinations(string $start = 'halmstad', string $end = 'stockholm')
    {

        $destinations = $this->service->getAllDestinations($start, $end);

        return response($destinations);
    }
}
