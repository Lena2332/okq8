<?php

namespace App\Http\Controllers;

use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected BookingService $service;

    public function __construct(
        BookingService $service
    )
    {
        $this->service = $service;
    }

    public function calculate( Request $request)
    {
       $stops = $request->stops;
       $approximateStops = $this->service->getAproximateStops($stops);

       return response($approximateStops);
    }

}
