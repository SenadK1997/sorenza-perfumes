<?php

namespace Database\Seeders;

use App\Models\Perfume;
use Illuminate\Database\Seeder;
use App\Enums\PerfumeGender;

class PerfumeSeeder extends Seeder
{
    public function run()
    {
        $perfumes = [
            [
                'name' => '01',
                'gender' => PerfumeGender::UNISEX,
                'inspired_by' => ucwords(fake()->words(2, true)),
                'price' => 60,
                'discount_percentage' => 10,
                'base_price' => 30,
                'main_image' => 'perfume01.jpg',
                'tag' => 'fresh',
                'accords' => [
                    ['name' => 'Woody', 'percentage' => 50],
                    ['name' => 'Amber', 'percentage' => 30],
                    ['name' => 'Citrus', 'percentage' => 20],
                ],
            ],
            [
                'name' => '02',
                'gender' => PerfumeGender::MALE,
                'inspired_by' => ucwords(fake()->words(2, true)),
                'price' => 60,
                'discount_percentage' => null,
                'base_price' => 30,
                'main_image' => 'perfume02.jpg',
                'tag' => 'floral',
                'accords' => [
                    ['name' => 'Rose', 'percentage' => 60],
                    ['name' => 'Jasmine', 'percentage' => 40],
                ],
            ],
            [
                'name' => '03',
                'gender' => PerfumeGender::FEMALE,
                'inspired_by' => ucwords(fake()->words(2, true)),
                'price' => 60,
                'discount_percentage' => 5,
                'base_price' => 30,
                'main_image' => 'perfume03.jpg',
                'tag' => 'spicy',
                'accords' => [
                    ['name' => 'Cinnamon', 'percentage' => 70],
                    ['name' => 'Clove', 'percentage' => 30],
                ],
            ],
            [
                'name' => '04',
                'gender' => PerfumeGender::UNISEX,
                'inspired_by' => ucwords(fake()->words(2, true)),
                'price' => 60,
                'discount_percentage' => 15,
                'base_price' => 30,
                'main_image' => 'perfume04.jpg',
                'tag' => 'woody',
                'accords' => [
                    ['name' => 'Sandalwood', 'percentage' => 80],
                    ['name' => 'Vanilla', 'percentage' => 20],
                ],
            ],
        ];

        foreach ($perfumes as $perfume) {
            Perfume::create($perfume);
        }
    }
}
