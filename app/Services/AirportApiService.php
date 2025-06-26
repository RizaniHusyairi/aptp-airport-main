<?php
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Exception\RequestException;

class AirportApiService
{
    protected $baseUrl;
    protected $timeout;
    protected $baseUrlCuaca;

    public function __construct()
    {
        $this->baseUrl = config('services.bandara.base_uri');
        $this->timeout = config('services.bandara.timeout');
        $this->baseUrlCuaca = "https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=64.72.05.1004";
        
    }

    /**
     * Get flight departures data from API
     */
    public function getDepartures($page = 1)
    {
        return Cache::remember("flight_departures_page_{$page}", now()->addMinutes(15), function () use ($page) {
            try {
                $response = Http::timeout($this->timeout)
                    ->get($this->baseUrl . '/keberangkatan', ['page' => $page]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('Departures API Request Failed', [
                    'url' => $this->baseUrl . '/keberangkatan',
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return null;
            } catch (\Exception $e) {
                Log::error('Departures API Connection Error', [
                    'url' => $this->baseUrl . '/keberangkatan',
                    'error' => $e->getMessage()
                ]);

                return null;
            }
        });
    }

    /**
     * Get all departures list across all pages
     */
    public function getDeparturesList()
    {
        $allData = [];
        $page = 1;

        do {
            $data = $this->getDepartures($page);

            if (!$data || !isset($data['data']['sukses']) || !$data['data']['sukses']) {
                break;
            }

            $allData = array_merge($allData, $data['data']['result']['data'] ?? []);
            $nextPageUrl = $data['data']['result']['next_page_url'] ?? null;
            $page++;
        } while ($nextPageUrl);

        return $allData;
    }

    /**
     * Get flight arrivals data from API
     */
    public function getArrivals($page = 1)
    {
        return Cache::remember("flight_arrivals_page_{$page}", now()->addMinutes(15), function () use ($page) {
            try {
                $response = Http::timeout($this->timeout)
                    ->get($this->baseUrl . '/kedatangan', ['page' => $page]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('Arrivals API Request Failed', [
                    'url' => $this->baseUrl . '/kedatangan',
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return null;
            } catch (\Exception $e) {
                Log::error('Arrivals API Connection Error', [
                    'url' => $this->baseUrl . '/kedatangan',
                    'error' => $e->getMessage()
                ]);

                return null;
            }
        });
    }

    /**
     * Get detailed arrivals list
     */
    public function getArrivalsList()
    {
        $allData = [];
        $page = 1;

        do {
            $data = $this->getArrivals($page);

            if (!$data || !isset($data['data']['sukses']) || !$data['data']['sukses']) {
                break;
            }

            $allData = array_merge($allData, $data['data']['result']['data'] ?? []);
            $nextPageUrl = $data['data']['result']['next_page_url'] ?? null;
            $page++;
        } while ($nextPageUrl);

        return $allData;
    }


    /**
     * Get flight statistics (movements, arrivals, departures)
     */
    public function getFlightStats()
    {
        $departures = $this->getDepartures();
        $arrivals = $this->getArrivals();
        
        // Fallback data if API is not available
        if (!$departures || !isset($departures['data']['sukses'])) {
            return [
                'movements' => 0,
                'arrivals' => 0,
                'departures' => 0
            ];
        }

        return [
            'movements' => 0,
            'arrivals' => $arrivals['data']['result']['total'] ?? 0,
            'departures' => $departures['data']['result']['total'] ?? 0
        ];
    }

    public function getCurrentWeather()
    {
        return Cache::remember('current_weather_data', now()->addMinutes(30), function () {
        
        //     $client = new Client([
        //        'base_uri' => $this->baseUrlCuaca,
        //        'timeout' => $this->timeout,
        //    ]);
           
           try {
               // REFAKTOR: Menggunakan Http Facade agar konsisten
                $response = Http::timeout($this->timeout)->get($this->baseUrlCuaca);

                if ($response->failed()) {
                    Log::error('Failed to fetch weather data from BMKG', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    return null;
                }

                $data = $response->json();
   
               // Ambil cuaca saat ini berdasarkan waktu terdekat
               $currentTime = now()->setTimezone('Asia/Makassar');
               $weatherData = $data['data'][0]['cuaca'][0]; // Ambil data cuaca pertama untuk hari ini
   
               foreach ($weatherData as $forecast) {
                   $forecastTime = \Carbon\Carbon::parse($forecast['local_datetime'], 'Asia/Makassar');
                   if ($forecastTime->greaterThanOrEqualTo($currentTime)) {
                       return [
                           'temperature' => $forecast['t'],
                           'weather_desc' => $forecast['weather_desc'],
                           'weather_icon' => $forecast['image'],
                           'humidity' => $forecast['hu'],
                           'wind_speed' => $forecast['ws'],
                           'wind_direction' => $forecast['wd'],
                           'local_datetime' => $forecast['local_datetime'],
                       ];
                   }
               }
   
               // Jika tidak ada data yang sesuai, kembalikan data pertama
               return [
                   'temperature' => $weatherData[0]['t'],
                   'weather_desc' => $weatherData[0]['weather_desc'],
                   'weather_icon' => $weatherData[0]['image'],
                   'humidity' => $weatherData[0]['hu'],
                   'wind_speed' => $weatherData[0]['ws'],
                   'wind_direction' => $weatherData[0]['wd'],
                   'local_datetime' => $weatherData[0]['local_datetime'],
               ];
           } catch (RequestException $e) {
               Log::error('Failed to fetch weather data from BMKG: ' . $e->getMessage());
               return null;
           }
        });
    }
}
