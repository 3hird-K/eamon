<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RadarService
{
    public $rad_pub_key;
    protected $rad_ser_secret;

    public function __construct()
    {
        $this->rad_pub_key = env('LIVE_CLIENT_PUB');
        $this->rad_ser_secret = env('LIVE_SERVER_SECRET'); // Secret key for the backend requests
    }

    public function countrySearch(string $query)
    {
        $endpoint = "https://api.radar.io/v1/search/autocomplete";

        $response = Http::withHeaders([
            'Authorization' => $this->rad_ser_secret,
        ])->get($endpoint, [
            'query' => $query,
            'limit' => 10,
        ]);

        if ($response->successful()) {
            // Return the results as an array
            return $response->json();
        }

        // If not successful, return an error or empty array
        return [
            'error' => 'Unable to fetch data from Radar API',
        ];
    }
}
