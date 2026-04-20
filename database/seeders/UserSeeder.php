<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

    $hsakha = City::where('name', 'Hsakha')->first();
        $erbil = City::where('name', 'Erbil')->first();
        $laravelSkill = Skill::where('name', 'Laravel')->first();

        $client = User::create([
        'first_name' => 'HireHub',
        'last_name'  => 'Client',
        'email' => 'client@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'city_id' => $erbil->id,
    ]);



    $freelancerUser = User::create([
        'first_name' => 'ُEbla',
        'last_name'  => 'ali',
        'email' => 'Ebla@example.com',
        'password' => Hash::make('password'),
        'role' => 'freelancer',
        'city_id' => $hsakha->id,
    ]);

$profile = $freelancerUser->profile()->create([
        'bio' => 'Laravel developer with one year of experience',
        'hourly_rate' => 25.00,
        'phone_number' => '963912345678',
        'is_verified' => true,
        'portfolio_links' => ['https://github.com/Ebla-a'],

    ]);

    $profile->skills()->attach([$laravelSkill->id => ['years_of_experience' => 1]]);

    }
}
