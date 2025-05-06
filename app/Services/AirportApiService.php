<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class AirportApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.bandara.base_uri');
    }

    public function getKeberangkatan()
    {
        $response = Http::get("{$this->baseUrl}/keberangkatan");
        return $response->successful() ? $response->json() : [];
    }

    public function getKedatangan()
    {
        $response = Http::get("{$this->baseUrl}/kedatangan");

        return $response->successful() ? $response->json() : [];
    }
}
