<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use App\Models\Perfume;
use App\Enums\Canton;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('sr_Latn_BA'); // Koristimo lokalizaciju za BiH
        // $sellers = User::role('seller')->pluck('id')->toArray();
        $perfumes = Perfume::pluck('id')->toArray();

        for ($i = 0; $i < 50; $i++) {
            Customer::create([
                'full_name' => $faker->name,
                'phone' => $faker->phoneNumber,
                'address_line_1' => $faker->streetAddress,
                'address_line_2' => $faker->optional()->secondaryAddress,
                'city' => $faker->city,
                'zipcode' => $faker->postcode,
                'email' => $faker->unique()->safeEmail,
                
                // Nasumično dodijeli prodavača ili ostavi null
                // 'user_id' => Arr::random([null, ...$sellers]),
                
                // Uzimamo nasumičnu vrijednost iz tvog Enuma
                'canton' => Arr::random(Canton::cases())->value,
                
                // Simuliramo interese kao niz nasumičnih ID-jeva parfema
                'interests' => Arr::random($perfumes, rand(1, 28)),
                
                'suggestions' => $faker->sentence,
            ]);
        }
    }
}