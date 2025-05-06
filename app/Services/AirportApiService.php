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
        $response = Http::get("{$this->baseUrl}/departure");
        return $response->successful() ? $response->json() : [];
    }

    public function getKedatangan()
    {
        $response = Http::get("{$this->baseUrl}/arrival");
        
        return $response->successful() ? $response->json() : [];
    }
}
