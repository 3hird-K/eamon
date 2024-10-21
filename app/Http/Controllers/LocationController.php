<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RadarService;

class LocationController extends Controller
{
    protected $radarService;

    public function __construct(RadarService $radarService)
    {
        $this->radarService = $radarService;
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        // Call the Radar service to search for countries
        $result = $this->radarService->countrySearch($query);

        // Return the results as a JSON response
        return response()->json($result);
    }
}
