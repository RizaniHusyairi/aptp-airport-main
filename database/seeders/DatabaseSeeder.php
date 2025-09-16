<?php

namespace Database\Seeders;

use App\Models\Airport;
use App\Models\ExtendAdvance;
use App\Models\Finance;
use App\Models\ImmediateInformation;
use App\Models\Zone;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            ServiceSeeder::class,
            FlightSeeder::class,
            NewsSeeder::class,
            LetterSeeder::class,
            FinanceSeeder::class,
            AirFreightTrafficSeeder::class,
            TourismSeeder::class,
            FacilitySeeder::class,
            ImmediateInformationSeeder::class,
            PeriodicDocumentSeeder::class,
            PpidRegulationSeeder::class,
            InformationServiceReportSeeder::class,
            EvergreenInformationSeeder::class,
            
            ExtendAdvanceSettingSeeder::class,

        ]);
    }
}
