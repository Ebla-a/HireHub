<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;

use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations =
        [
        ['name' => 'kurdstan', 'code' => 'KR', 'cities' => ['Erbil', 'Sulaymaniyah', 'Duhok', 'Kirkuk', 'Halabja', 'Zakho']],
        ['name' => 'Syria', 'code' => 'SY', 'cities' => ['Damascus', 'Aleppo', 'Homs', 'Latakia', 'Hama', 'Tartus', 'Hsakha']],
        ['name' => 'Palestine', 'code' => 'PS', 'cities' => [ 'Gaza', 'Ramallah', 'Nablus', 'Bethlehem', 'Haifa']],

        ];

        foreach($locations as $location)
            {
                $country = Country::updateOrCreate(
                    ['code' => $location['code']],
                ['name' => $location['name']]
                );  
                   foreach ($location['cities'] as $cityName) {
                City::updateOrCreate([
                    'name' => $cityName,
                    'country_id' => $country->id
                ]);
    } 
            }
        
}


}