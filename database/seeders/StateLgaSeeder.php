<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateLgaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Lagos' => ['Ikeja', 'Lagos Island', 'Lagos Mainland', 'Victoria Island'],
            'Abuja FCT' => ['Abuja Municipal', 'Bwari', 'Gwagwalada', 'Kuje'],
            'Rivers' => ['Port Harcourt', 'Obio-Akpor', 'Eleme'],
            'Kano' => ['Kano Municipal', 'Dala', 'Gwale'],
            'Kaduna' => ['Kaduna North', 'Kaduna South', 'Zaria'],
        ];

        foreach ($data as $stateName => $lgas) {
            $state = \App\Models\State::create(['name' => $stateName]);
            foreach ($lgas as $lgaName) {
                $lga = \App\Models\Lga::create(['state_id' => $state->id, 'name' => $lgaName]);
                
                // create 2-3 dummy event centers for each LGA
                for ($i = 1; $i <= rand(2, 4); $i++) {
                    \App\Models\EventCenter::create([
                        'name' => 'Premium Event Center ' . $lgaName . ' ' . $i,
                        'state_id' => $state->id,
                        'lga_id' => $lga->id,
                        'city_town' => $lgaName,
                        'full_address' => rand(1, 100) . ' Main Street, ' . $lgaName,
                        'capacity' => rand(100, 2000),
                        'starting_price' => rand(100000, 1500000),
                        'phone_number' => '+23480000000' . rand(10, 99),
                        'hall_type' => ['indoor', 'outdoor', 'mixed'][rand(0,2)],
                        'has_parking' => (bool)rand(0,1),
                        'has_generator' => true,
                        'has_decoration' => (bool)rand(0,1),
                        'has_catering' => (bool)rand(0,1),
                        'features' => json_encode(['Wedding', 'Conference']),
                        'rating' => rand(30, 50) / 10,
                        'reviews_count' => rand(5, 50),
                    ]);
                }
            }
        }
    }
}
