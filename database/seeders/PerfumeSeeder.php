<?php

namespace Database\Seeders;

use App\Models\Perfume;
use Illuminate\Database\Seeder;

class PerfumeSeeder extends Seeder
{
    public function run()
    {
        $perfumes = [
            [
                'name' => '01',
                'price' => 60,
                'discount_percentage' => 10,
                'base_price' => 30,
                'main_image' => 'perfume01.jpg',
                'tag' => 'fresh',
                'accords' => json_encode([
                    ['name' => 'Woody', 'percentage' => 50],
                    ['name' => 'Amber', 'percentage' => 30],
                    ['name' => 'Citrus', 'percentage' => 20],
                ]),
            ],
            [
                'name' => '02',
                'price' => 60,
                'discount_percentage' => null,
                'base_price' => 30,
                'main_image' => 'perfume02.jpg',
                'tag' => 'floral',
                'accords' => json_encode([
                    ['name' => 'Rose', 'percentage' => 60],
                    ['name' => 'Jasmine', 'percentage' => 40],
                ]),
            ],
            [
                'name' => '03',
                'price' => 60,
                'discount_percentage' => 5,
                'base_price' => 30,
                'main_image' => 'perfume03.jpg',
                'tag' => 'spicy',
                'accords' => json_encode([
                    ['name' => 'Cinnamon', 'percentage' => 70],
                    ['name' => 'Clove', 'percentage' => 30],
                ]),
            ],
            [
                'name' => '04',
                'price' => 60,
                'discount_percentage' => 15,
                'base_price' => 30,
                'main_image' => 'perfume04.jpg',
                'tag' => 'woody',
                'accords' => json_encode([
                    ['name' => 'Sandalwood', 'percentage' => 80],
                    ['name' => 'Vanilla', 'percentage' => 20],
                ]),
            ],
        ];

        foreach ($perfumes as $perfume) {
            Perfume::create($perfume);
        }
    }
}
