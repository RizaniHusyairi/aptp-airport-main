<?php
namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class AirportApiService
{
    protected $baseUrl;
    protected $baseUrlCuaca;

    public function __construct()
    {
        $this->baseUrl = config('services.bandara.base_uri');
        $this->baseUrlCuaca = "https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=64.72.05.1004";
        
    }

    public function getKeberangkatan()
    {
        $response = Http::get("{$this->baseUrl}/keberangkatan");
        if ($response->failed()) {
            return [];
        }
        return $response->successful() ? $response->json() : [];
    }

    public function getKedatangan()
    {
        $response = Http::get("{$this->baseUrl}/kedatangan");
        if ($response->failed()) {
            return [];
        }

        return $response->successful() ? $response->json() : [];
    }

    public function getCuaca()
    {
       try {
            $response = Http::get($this->baseUrlCuaca);
            
            if ($response->successful()) {
                $data = $response->json();
                $forecasts = $data['data'][0]['cuaca'] ?? [];
                
                // Waktu utama (WITA): 07:00, 13:00, 16:00, 19:00
                $now = Carbon::now('Asia/Makassar'); // 2025-05-14 10:12 WITA
                $twoHoursLater = $now->copy()->addHours(2); // 12:12 WITA

                $nearestForecast = null;
                $minTimeDiff = null;

                foreach ($forecasts as $dayForecasts) {
                    foreach ($dayForecasts as $forecast) {
                        $forecastTime = Carbon::parse($forecast['local_datetime'], 'Asia/Makassar');
                        
                        // Hanya pilih waktu setelah sekarang
                        if ($forecastTime->greaterThan($now)) {
                            $timeDiff = $forecastTime->diffInSeconds($now);
                            
                            // Pilih prakiraan dengan selisih waktu terkecil
                            if ($minTimeDiff === null || $timeDiff < $minTimeDiff) {
                                $minTimeDiff = $timeDiff;
                                $nearestForecast = [
                                    'time' => $forecastTime->format('H:i'),
                                    'temperature' => $forecast['t'],
                                    'weather_desc' => $forecast['weather_desc'],
                                    'weather_image' => $forecast['image'],
                                    'humidity' => $forecast['hu'],
                                    'wind_speed' => round($forecast['ws'] * 3.6, 1), // m/s ke km/h
                                    'wind_direction' => $forecast['wd'],
                                ];
                            }
                        }
                    }
                }

                if ($nearestForecast) {
                    return [
                        'success' => true,
                        'data' => $nearestForecast,
                        'location' => $data['lokasi']['desa'] . ', ' . $data['lokasi']['kecamatan'],
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'Tidak ada prakiraan cuaca dalam 2 jam ke depan.',
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal mengambil data cuaca dari BMKG.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data cuaca: ' . $e->getMessage(),
            ];
        }

        // return $response->successful() ? $response->json() : [];
    }
}
